<?php

namespace VariationCrossSelling\Repositories;

use IO\Services\BasketService;
use Plenty\Modules\Webshop\ItemSearch\Factories\VariationSearchFactory;
use Plenty\Modules\Webshop\ItemSearch\Services\ItemSearchService;
use Plenty\Plugin\Log\Loggable;

/**
 * Repository for initializing and changing data in the UI
 */
class frontendRepository
{
    use Loggable;

    public function getVarDataByIdFrontend($varId,$lang)
    {
        $searchFactory              = pluginApp(VariationSearchFactory::class);
        $itemSearchService          = pluginApp(ItemSearchService::class);

        $searchFactory
            ->hasVariationId($varId)
            ->withImages()
            ->isVisibleForClient()
            ->hasPriceForCustomer()
            ->isActive()
            ->withPrices()
            ->withLanguage($lang)
            ->withResultFields(['*'])
            ->withDefaultImage()
        ;

        $resultToSend  = $itemSearchService->getResults(['variation' => $searchFactory]);

        return $resultToSend;
    }
    public function addToBasket($items)
    {
        $basketService = pluginApp(BasketService::class);

        $returnValue = [];

        foreach ($items as $key=>$item)
        {
            $data['variationId'] = $item['variationId'];
            $data['quantity']    = $item['quantity'];

            $getData = $basketService->addBasketItem($data);

            if($getData)
            {
                $returnValue[$key]['error'] = $getData;
                $returnValue[$key]['error']['variationName'] = $item['name'];
            }

            $returnValue[$key]['basketItems']   = $basketService->getBasketItemsForTemplate();
            $returnValue[$key]['basket']        = $basketService->getBasketForTemplate();
        }

        return $returnValue;
    }
}
