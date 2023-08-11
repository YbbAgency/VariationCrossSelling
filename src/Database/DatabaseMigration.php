<?php

namespace VariationCrossSelling\Database;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use VariationCrossSelling\Models\CrossSellerModel;

/**
 * Class for migrating the set datatable
 */
class DatabaseMigration
{
    /**
     * runs migration of set model
     * @param Migrate $migrate
     */
    public function run(Migrate $migrate)
    {
        $migrate->createTable(CrossSellerModel::class);
    }
}