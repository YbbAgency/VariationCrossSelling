<?php

namespace VariationCrossSelling\Controllers;


use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Log\Loggable;
use VariationCrossSelling\Models\CrossSellerModel;
use VariationCrossSelling\Repositories\uiRepository;


/**
 * Controller for initializing and changing data in the UI
 */
class UIController
{
    use Loggable;
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->uiRepos = pluginApp(uiRepository::class);
        $this->request = pluginApp(Request::class);
    }


    /**
     * calls function that dynamically accesses plugin db
     * @param Request $request
     * @return array
     */
    public function dynamicGet(Request $request)
    {
        $data = $request->all();

        $uiRepository = pluginApp(uiRepository::class);

        $dynamicEntries = $uiRepository->getTableDynamic($data);

        return
            [
                "dynamicEntries" => $dynamicEntries,
            ];
    }

    /**
     * get initial data required for plugin ui
     * @param int $webStoreId
     * @return mixed
     */
    public function getInitialUiData($webStoreId = 0)
    {
        $data =  $this->uiRepos->getInitialData($webStoreId);

        return $data;
    }
    public function getCrossSellerForVar($varId,$lang)
    {


        $result  = $this->uiRepos->getCrossSellerForVar($varId,$lang);

        return $result;
    }

    /**
     * calls function that deletes specific cross seller
     * @return array
     */
    public function deleteCrossSeller()
    {
        $requestData = $this->request->all();

        $deleteFrom = $requestData["deleteFrom"] ? $requestData["deleteFrom"] : 0;

        $toDelete  = $requestData["toDelete"] ? $requestData["toDelete"] : [];

        if($deleteFrom > 0 && sizeof($toDelete) > 0)
        {
            $toDeleteIds = [];

            for($i = 0 ; $i < sizeof($toDelete);$i++)
            {
                $toDeleteIds[] = $toDelete[$i]["varId"];
            }
            if(sizeof($toDeleteIds) >0)
            {
                return $this->uiRepos->deleteCrossSeller($deleteFrom,$toDeleteIds);
            }
            else{
                return ["success"=>false,"entry"=>[],"exception"=>"sizeof(toDelete) is < 0"];
            }
        }
        else{
            return ["success"=>false,"entry"=>[],"exception"=>"deleteFrom is < 0 or sizeof(toDelete) is < 0"];
        }
    }

    /**
     * call function that updates cross seller position
     * @return array
     */
    public function updatePosition()
    {
        $requestData = $this->request->all();

        $toUpdate = $requestData["toUpdate"] ? $requestData["toUpdate"] : [];
        $updateOn = $requestData["updateOn"] ? $requestData["updateOn"] : 0;


        if($updateOn > 0 && sizeof($toUpdate) > 0)
        {
            return $this->uiRepos->updatePosition($updateOn,$toUpdate);
        }
        else{
            return ["success"=>false,"entry"=>[],"exception"=>"updateOn is < 1 or  sizeof(toUpdate) is < 1"];
        }
    }

    /**
     * calls function that adds cross seller
     * @return array
     */
    public function add()
    {
        $requestData = $this->request->all();

        $addTo = $requestData["addTo"] ? $requestData["addTo"] : [];

        $toAdd  = $requestData["toAdd"] ? $requestData["toAdd"] : [];

        if(sizeof($addTo) > 0 && sizeof($toAdd) > 0)
        {
            return $this->uiRepos->add($addTo,$toAdd);
        }
        else{
            return ["success"=>false,"entry"=>[],"exception"=>"addTo is < 0 or toAdd is < 0"];
        }

    }
}