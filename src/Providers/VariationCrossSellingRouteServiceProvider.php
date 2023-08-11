<?php

namespace VariationCrossSelling\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\Routing\Router;

/**
 * class for mapping routes
 */
class VariationCrossSellingRouteServiceProvider extends RouteServiceProvider
{
    /**
     * default route provider for registering routes through apiv1
     * @param ApiRouter $api
     */

      public function map(Router $router,ApiRouter $api)
      {
          $router->post('variationCrossSeller/frontend/addToBasket/'    						  ,'VariationCrossSelling\Controllers\frontendController@addToBasket');
          $router->get("variationCrossSeller/getCrossSellerForVar/{varId}/{lang}"       , "VariationCrossSelling\Controllers\UIController@getCrossSellerForVar");

          $router->get("deleteCrossSellerModel/{id}",'VariationCrossSelling\Controllers\CrossSellerModelController@deleteCrossSellerModel');
          $router->get("getAll",'VariationCrossSelling\Controllers\CrossSellerModelController@getAll');
          $router->get("getCrossSellerModelByVariationid/{varId}",'VariationCrossSelling\Controllers\CrossSellerModelController@getCrossSellerModelByVariationid');


          $api->version(['v1'], ['namespace' => 'VariationCrossSelling\Controllers'], function (ApiRouter $api)
          {
              $api->get("variationCrossSeller/getInitialUiData/{webStoreId}"                        , "UIController@getInitialUiData");

              $api->post("variationCrossSeller/updatePosition/"                                     , "UIController@updatePosition");
              $api->post("variationCrossSeller/dynamicGet/"                                         , "UIController@dynamicGet");
              $api->post("variationCrossSeller/deleteCrossSeller/"                                  , "UIController@deleteCrossSeller");
              $api->post("variationCrossSeller/addCrossSeller/"                                     , "UIController@add");
          });
      }
}