<?php

namespace VariationCrossSelling\Repositories;

use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Plugin\CachingRepository;
use Plenty\Plugin\Log\Loggable;
use VariationCrossSelling\Contract\CrossSellerModelContract;
use VariationCrossSelling\Models\CrossSellerModel;

class CrossSellerModelRepository implements CrossSellerModelContract
{
    use Loggable;

    const PREFIX = 'spotlight_variationcrossseller_varid_';

    public function getCrossSellerModelById($id): CrossSellerModel
    {
        $database = pluginApp(DataBase::class);

        $entries =   $database
            ->query(CrossSellerModel::class)
            ->where('id','=',$id)
            ->get();

        if(sizeof($entries) > 0 )
        {
            return $entries[0];
        }
        else{
            return pluginApp(CrossSellerModel::class);
        }
    }
    public function setCrossSellerModel(CrossSellerModel $crossSellerModel): CrossSellerModel
    {
        $database = pluginApp(DataBase::class);

        if($crossSellerModel->id > 0)
        {
            $crossSellerModel->updatedAt = time();
        }
        else{
            $crossSellerModel->createdAt = time();
            $crossSellerModel->updatedAt = time();
        }

        $cache = pluginApp(CachingRepository::class);

        $cKey  =  $cache->forget(
            self::PREFIX.$crossSellerModel->varId
        );

        $crossSellerModel = $database->save($crossSellerModel);

        $cache->add(
            self::PREFIX.$crossSellerModel->varId,
            $crossSellerModel , 10080
        );


        return $crossSellerModel;
    }

    public function deleteCrossSellerModel($id): bool
    {
        if($id > 0)
        {
            $database = pluginApp(DataBase::class);

            $entries =   $database
                ->query(CrossSellerModel::class)
                ->where('id','=',$id)
                ->get();

            if(sizeof($entries) >0)
            {
                $entry = $entries[0];

                $database = pluginApp(DataBase::class);

                $deletedBoolean =  $database->delete($entry);

                if($deletedBoolean == true)
                {
                    $cache = pluginApp(CachingRepository::class);

                    $cKey   =   $cache->forget(
                        self::PREFIX.$entry->varId
                    );
                }

                return $deletedBoolean;
            }
        }
        return false;
    }

    public function getAll(): array
    {
        $database = pluginApp(DataBase::class);

        $entries =   $database
            ->query(CrossSellerModel::class)
            ->get();

        return $entries;
    }


    public function getCrossSellerModelByVariationId($varId): CrossSellerModel
    {
        if($varId > 0)
        {
            $cache 	  				=   pluginApp(CachingRepository::class);

            $cKey                   =   $cache->get(
                self::PREFIX.$varId,null
            );

            if(is_null($cKey))
            {
                $database = pluginApp(DataBase::class);

                $entries =   $database
                    ->query(CrossSellerModel::class)
                    ->where('varId','=',$varId)
                    ->get();

                if(sizeof($entries) >0)
                {
                    $cache 	    =   pluginApp(CachingRepository::class);

                    $cache->add(
                        self::PREFIX.$varId
                        ,
                        $entries[0] , 10080
                    );

                    return $entries[0];
                }
            }
            else{
                return $cKey;
            }

        }
        return pluginApp(CrossSellerModel::class);
    }


}