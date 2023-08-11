<?php

namespace VariationCrossSelling\Controllers;

use Plenty\Plugin\Http\Request;
use VariationCrossSelling\Repositories\frontendRepository;
use VariationCrossSelling\Repositories\uiRepository;

class frontendController
{
    /**
     * calls function that adds items to basket ( webshop )
     * @param Request $request
     * @return mixed
     */
    public function addToBasket(Request $request)
    {
        $data = $request->all();

        $items = $data['items'];

        return pluginApp(frontendRepository::class)->addToBasket($items);
    }
}