<?php
namespace VariationCrossSelling\Contract;

use VariationCrossSelling\Models\CrossSellerModel;

/**
 * Contract for cross seller model
 */
interface CrossSellerModelContract
{
    public function getCrossSellerModelById($id):CrossSellerModel;

    public function setCrossSellerModel(CrossSellerModel $crossSellerModel):CrossSellerModel;

    public function deleteCrossSellerModel($id):bool;

    public function getAll():array;

    //
    public function getCrossSellerModelByVariationId($varId):CrossSellerModel;
}