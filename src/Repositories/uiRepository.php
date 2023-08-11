<?php

namespace VariationCrossSelling\Repositories;

use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Modules\Item\Manufacturer\Contracts\ManufacturerRepositoryContract;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Modules\System\Contracts\WebstoreRepositoryContract;
use Plenty\Modules\Tag\Contracts\TagRepositoryContract;
use Plenty\Modules\Webshop\ItemSearch\Factories\VariationSearchFactory;
use Plenty\Modules\Webshop\ItemSearch\Services\ItemSearchService;
use Plenty\Plugin\Log\Loggable;
use VariationCrossSelling\Models\CrossSellerModel;
use \VariationCrossSelling\Repositories\CrossSellerModelRepository;
use \VariationCrossSelling\Repositories\frontendRepository;

/**
 * Repository for initializing and changing data in the UI
 */
class uiRepository
{
    /**
     * default constructor
     */
    use Loggable;
    public function __construct()
    {
        $this->database = pluginApp(DataBase::class);
    }

    /**
     * get Data required for initial ui load
     * @param $webStoreId
     * @return array
     */
    public function getInitialData($webStoreId)
    {
        $webStoreContract = pluginApp(WebstoreRepositoryContract::class);

        $webStoreConfig = $webStoreContract->loadAll();

        $categoryRepositoryContract = pluginApp(CategoryRepositoryContract::class);

        $categoryTree =  $categoryRepositoryContract->getLinklistTree('item',"de",$webStoreId);

        $ManufacturerRepositoryContract = pluginApp(ManufacturerRepositoryContract::class);

        $manufacturer = $ManufacturerRepositoryContract->all();

        $TagRepositoryContract = pluginApp(TagRepositoryContract::class);

        $tags  = $TagRepositoryContract->listTags();

        return
            [
                "tags"=>$tags,
                "manufacturer"=>$manufacturer,
                "webStoreConfig"=>$webStoreConfig,
                "categoryTree" =>$categoryTree
            ];
    }

    /** Get CrossSellers from Plugin DB for a variation Id
     * @param $varId
     * @param string $lang
     * @return array
     */
    public function getCrossSellerForVar($varId,$lang = "de")
    {
        $varId = intval($varId);

        if($varId > 0)
        {

            $selectedVar = self::getVarDataById($varId);

            try
            {
                $selectedVar = $selectedVar["variation"]["documents"][0];

                $database = pluginApp(DataBase::class);

                $entry =   $database
                    ->query(CrossSellerModel::class)
                    ->where('varId','=',$varId)
                    ->get();

                $varData = [];

                if(sizeof($entry) > 0)
                {
                    $entry = $entry[0];

                    if(isset($entry))
                    {
                        $frontendRepository = pluginApp(frontendRepository::class);

                        for($i = 0; $i < sizeof($entry->crossSeller) ; $i++)
                        {
                            $currentVarId = $entry->crossSeller[$i]["varId"];

                            $data = self::getVarDataById($currentVarId);

                            if(sizeof($data) > 0)
                            {
                                $data["variation"]["documents"][0]["position"]  = $entry->crossSeller[$i]["position"];
                                $varData[] = $data["variation"]["documents"][0];
                            }
                        }
                    }
                }

                $rebuiltArray = [];


                for($i = 0 ; $i < sizeof($varData) ; $i++)
                {
                    if(isset($varData[$i]))
                    {
                        $rebuiltArray[] = $varData[$i];
                    }
                }

                usort($rebuiltArray, function($a, $b) {
                    return $a['position'] <=> $b['position'];
                });

                return ["selectedVar"=>$selectedVar,"success"=>true,"vars"=>$rebuiltArray];
            }
            catch (\Exception $exception)
            {
                return ["selectedVar"=>[],"success"=>false,"vars"=>[],"exception"=>$exception];
            }
        }
        return ["selectedVar"=>[],"success"=>false,"vars"=>[],"exception"=>"varId < 0"];
    }

    /** adds cross seller to specific variation
     * @param $addTo
     * @param $toAdd
     * @return array
     */
    public function add($addTo,$toAdd)
    {
        if(sizeof($toAdd) > 0 )
        {
            $database = pluginApp(DataBase::class);

            try {
                $entry =   $database
                    ->query(CrossSellerModel::class)
                    ->where('varId','=',$addTo["varId"])
                    ->get();

                $entry = $entry[0];

                if(isset($entry))
                {
                    for($i = 0 ; $i < sizeof($toAdd) ; $i++)
                    {
                        $toPush = ["varId"=>$toAdd[$i]["varId"],"position"=>$toAdd[$i]["position"]];

                        for($a = 0; $a < sizeof($entry->crossSeller) ; $a++)
                        {
                            if($entry->crossSeller[$a]["varId"] != $toPush["varId"])
                            {
                                if($a+1 == sizeof($entry->crossSeller))
                                {
                                    $entry->crossSeller[] = $toPush;
                                }
                            }
                            else{
                                break;
                            }
                        }
                    }
                }
                else{
                    $entry = pluginApp(CrossSellerModel::class);

                    $entry->varId = $addTo["varId"];

                    for($i = 0 ; $i < sizeof($toAdd) ; $i++)
                    {
                        $toPush = ["varId"=>$toAdd[$i]["varId"],"position"=>$toAdd[$i]["position"]];

                        $entry->crossSeller[] = $toPush;
                    }
                    $entry->itemId              = $addTo["itemId"] ? : 0;
                    $entry->varNumber           = $addTo["varNumber"] ? : '';
                    $entry->varName             = $addTo["itemName"] ? : '';
                    $entry->itemType            = $addTo["itemType"] ? : 'default';
                    $entry->manufacturer        = $addTo["manufacturer"]  ? : '';
                    $entry->externalVarId       = $addTo["externalVarId"] ? : 0;
                    if(strlen(implode(",", $addTo["client"])) > 0)
                    {
                        $entry->client              = implode(",", $addTo["client"]);
                    }
                    else{
                        $entry->client              = '';
                    }
                }

                $crossSellerModelRepo = pluginApp(CrossSellerModelRepository::class);

                $crossSellerModelRepo->setCrossSellerModel($entry);

                return ["success"=>true,"entry"=>$entry];
            }
            catch (\Exception $exception)
            {
                return ["success"=>false,"entry"=>'',"exception"=>$exception];
            }
        }
        else{
            return ["success"=>false,"exception"=>" to add < 0 "];
        }
    }

    /**
     * Updates Position of CrossSeller in Variation
     * @param $updateOn
     * @param $toUpdate
     * @return array
     */
    public function updatePosition($updateOn,$toUpdate)
    {
        $database = pluginApp(DataBase::class);

        $entry =   $database
            ->query(CrossSellerModel::class)
            ->where('varId','=',$updateOn)
            ->get();

        try{
            $entry = $entry[0];

            for($a = 0 ; $a < sizeof($toUpdate) ; $a++) {
                for ($i = 0; $i < sizeof($entry->crossSeller); $i++) {
                    if ($entry->crossSeller[$i]["varId"] == $toUpdate[$a]['varId'])
                    {
                       $entry->crossSeller[$i]["position"] = $toUpdate[$a]["position"];
                       break;
                    }
                }
            }

            $crossSellerModelRepo = pluginApp(CrossSellerModelRepository::class);

            $crossSellerModelRepo->setCrossSellerModel($entry);

            return ["success"=>true,"entry"=>$entry];
        }
        catch (\Exception $exception)
        {
            return ["success"=>false,"exception"=>$exception];
        }
    }

    /**
     * deletes specfic CrossSellers from a variation
     * @param $varId
     * @param $varIdsToDelete
     * @return array
     */
    public function deleteCrossSeller($varId,$varIdsToDelete)
    {
        if(sizeof($varIdsToDelete) > 0)
        {
            $database = pluginApp(DataBase::class);

            $entry =   $database
                ->query(CrossSellerModel::class)
                ->where('varId','=',$varId)
                ->get();

            try{
                $entry = $entry[0];

                if(sizeof($varIdsToDelete) >= sizeof($entry->crossSeller))
                {
                    $crossSellerModelRepo = pluginApp(CrossSellerModelRepository::class);

                    $crossSellerModelRepo->deleteCrossSellerModel($entry->id);
                }
                else{
                    $crossSellerNew = [];

                    for($a = 0 ; $a < sizeof($entry->crossSeller) ; $a++)
                    {
                        for($i = 0 ; $i < sizeof($varIdsToDelete) ; $i++)
                        {
                            if($entry->crossSeller[$a]["varId"] !== $varIdsToDelete[$i])
                            {
                                if($i+1 == sizeof($varIdsToDelete))
                                {
                                    $crossSellerNew[] = $entry->crossSeller[$a];
                                }
                            }
                            else{
                                break;
                            }
                        }
                    }

                    $entry->crossSeller = $crossSellerNew;

                    $crossSellerModelRepo = pluginApp(CrossSellerModelRepository::class);

                    $crossSellerModelRepo->setCrossSellerModel($entry);
                }

                return ["success"=>true,"entry"=>$entry];
            }
            catch (\Exception $exception)
            {
                return ["success"=>false,"exception"=>$exception];
            }
        }
        else{
            return ["success"=>false,"exception"=>"varIdsToDelete is < 0"];
        }

    }

    /**
     * gets Plenty Data for a variation by its id
     * @param $varId
     * @return mixed
     */
    public function getVarDataById($varId)
    {

        //$variationSearchRepositoryContract->setFilters([
        //                'id' => $variationList
        //            ]);
        //            $variationSearchRepositoryContract->setSearchParams(
        //                [
        //                    'with' => [
        //                        'variationDefaultCategory' => null,
        //                        'variationCategories' => null,
        //                        'properties' => null,
        //                        'variationProperties' => null,
        //                        'variationSalesPrices' => null,
        //                        'images' => null,
        //                        'itemImages' => null,
        //                        'variationAttributeValues' => null,
        //                        'variationClients' => null
        //                    ]
        //                ]);
        //            $paginatedResult    =   $variationSearchRepositoryContract->search();

        $searchFactory              = pluginApp(VariationSearchFactory::class);
        $itemSearchService          = pluginApp(ItemSearchService::class);

        $searchFactory
            ->hasVariationId($varId)
            ->withLanguage('de')
            ->withImages()
            ->withPrices()
            ->withResultFields(['*'])
            ->withDefaultImage()
        ;

        $resultToSend  = $itemSearchService->getResults(['variation' => $searchFactory]);

        return $resultToSend;
    }

    /**
     * provides easy way to call plugin db
     * @param $data
     * @return array
     */
    public function getTableDynamic($data)
    {
//        $data =
//        [
//            "itemsPerPage" => 10,
//            "filter" =>
//            [
//                [
//                    "where" => "id",
//                    "operator" => '=',
//                    "value" => 1
//                ],
//                [
//                    "where" => "createdAt",
//                    "operator" => '>=',
//                    "value" => 'timestamp'
//                ]
//
//            ]
//        ];

        $databaseName       = $data['db'];
        $itemsPerPage		= $data['itemsPerPage'] ? $data['itemsPerPage'] : 20;
        $page				= $data['page'] ? ($data['page']) : 1;
        $orderBy 			= $data['orderBy'] ? ($data['orderBy']) : 'id';
        $orderDesc			= $data['orderDesc'] ? $data['orderDesc'] : 'desc';
        $filter 			= $data['filter'] ? $data['filter'] : [] ;

        if($databaseName == 'crossSeller')
        {
            $myDataBase = $this->database->query(CrossSellerModel::class);
        }

        for( $i = 0; $i < sizeof($filter); $i++)
        {
            $myDataBase->where($filter[$i]['where'],$filter[$i]['operator'],$filter[$i]['value']);
        }

        $itemsTotal			= $myDataBase->count();
        $pages				= ceil($itemsTotal / $itemsPerPage);

        $entries			= $myDataBase->orderBy($orderBy,$orderDesc)->forPage($page,$itemsPerPage)->get();

        $getItemData = $data['getItemData'] ? $data['getItemData'] : false ;

        if($getItemData == true)
        {
            $entriesWithData = [];
            if(isset($entries))
            {
                for($i = 0 ; $i < sizeof($entries) ; $i++)
                {
                    $var = self::getVarDataById($entries[$i]->varId);

                    if($var["variation"] && sizeof($var["variation"]["documents"]) > 0)
                    {
                        $entriesWithData[] = $var["variation"]["documents"][0];
                    };

                }
            }
            return
                [
                    "pages"		 	=> $pages,
                    "itemsTotal" 	=> $itemsTotal,
                    "entries" 		=> $entriesWithData
                ];
        }



        return
            [
                "pages"		 	=> $pages,
                "itemsTotal" 	=> $itemsTotal,
                "entries" 		=> $entries
            ];
    }
}