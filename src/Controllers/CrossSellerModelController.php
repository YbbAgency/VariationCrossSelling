<?php

namespace VariationCrossSelling\Controllers;

use Plenty\Plugin\CachingRepository;
use Plenty\Plugin\Http\Request;
use VariationCrossSelling\Contract\CrossSellerModelContract;
use VariationCrossSelling\Models\CrossSellerModel;

class CrossSellerModelController
{
    /**
     * gets a cross seller Model by its id
     * @param $id
     * @return false|string
     */
    public function getCrossSellerModelById($id)
    {
        $contract = pluginApp(CrossSellerModelContract::class);

        return json_encode($contract->getCrossSellerModelById($id));
    }

    /**
     * sets a cross seller model
     * @param Request $request
     * @return false|string
     */
    public function setCrossSellerModel(Request $request)
    {
        $requestData = $request->all();

        $entry = CrossSellerModel::toObject($requestData);

        $contract = pluginApp(CrossSellerModelContract::class);

        return json_encode($contract->setCrossSellerModel($entry));
    }

    /**
     * deletes a cross seller model by its id
     * @param $id
     * @return false|string
     */
    public function deleteCrossSellerModel($id)
    {
        $contract = pluginApp(CrossSellerModelContract::class);

        return json_encode($contract->deleteCrossSellerModel($id));
    }

    /**
     * Gets all CrossSellerModels
     * @return false|string
     */
    public function getAll()
    {
        $contract = pluginApp(CrossSellerModelContract::class);

        return json_encode($contract->getAll());
    }
    public function getCrossSellerModelByVariationid($varId)
    {
        $contract = pluginApp(CrossSellerModelContract::class);

        return json_encode($contract->getCrossSellerModelByVariationid($varId));
    }
}