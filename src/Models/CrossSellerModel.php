<?php

namespace VariationCrossSelling\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * Class SetModel
 *
 * @property int $id
 * @property int $varId
 * @property int $itemId
 * @property string $varNumber
 * @property string $varName
 * @property string $itemType
 * @property string $manufacturer
 * @property array $crossSeller
 * @property array $client
 * @property int $externalVarId
 * @property int $createdAt
 * @property int $updatedAt
 */
class CrossSellerModel extends Model
{
    public $id                  = 0;
    public $varId               = 0;
    public $itemId              = 0;
    public $varNumber           = '';
    public $varName             = '';
    public $itemType            = '';
    public $manufacturer        = '';
    public $crossSeller         = [];
    public $client              = 0;
    public $externalVarId       = '';
    public $createdAt           = 0;
    public $updatedAt           = 0;
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'VariationCrossSelling::CrossSellerModel';
    }

    /**
     * function to create Cross Seller Model from data array
     * @param $dataArray
     * @return CrossSellerModel
     */
    public static function toObject($dataArray):CrossSellerModel
    {
        $emptyModel = pluginApp(CrossSellerModel::class);

        $emptyModel->id = $dataArray["id"] ? $dataArray["id"] : 0;
        $emptyModel->varId = $dataArray["varId"] ? $dataArray["varId"] : 0;
        $emptyModel->itemId = $dataArray["itemId"] ? $dataArray["itemId"] : 0;
        $emptyModel->varNumber = $dataArray["varNumber"] ? $dataArray["varNumber"] : '';
        $emptyModel->varName = $dataArray["varName"] ? $dataArray["varName"] : '';
        $emptyModel->itemType = $dataArray["itemType"] ? $dataArray["itemType"] : '';
        $emptyModel->manufacturer = $dataArray["manufacturer"] ? $dataArray["manufacturer"] : '';
        $emptyModel->crossSeller = $dataArray["crossSeller"] ? $dataArray["crossSeller"] : [];
        $emptyModel->client = $dataArray["client"] ? $dataArray["client"] : 0;
        $emptyModel->externalVarId = $dataArray["externalVarId"] ? $dataArray["externalVarId"] : '';
        $emptyModel->createdAt = $dataArray["createdAt"] ? $dataArray["createdAt"] : 0;
        $emptyModel->updatedAt = $dataArray["updatedAt"] ? $dataArray["updatedAt"] : 0;

        return $emptyModel;
    }
}