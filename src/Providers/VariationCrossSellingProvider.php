<?php

namespace VariationCrossSelling\Providers;


use IO\Helper\ResourceContainer;
use IO\Helper\TemplateContainer;
use Plenty\Modules\ShopBuilder\Contracts\ContentWidgetRepositoryContract;
use Plenty\Modules\Webshop\Template\Providers\TemplateServiceProvider;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Templates\Twig;
use VariationCrossSelling\Contract\CrossSellerModelContract;
use VariationCrossSelling\Extensions\VariationCrossSeller;
use VariationCrossSelling\Repositories\CrossSellerModelRepository;
use VariationCrossSelling\Widgets\VariationCrossSellingWidget;
use VariationCrossSelling\Providers\VariationCrossSellingRouteServiceProvider;

/**
 * default serviceprovider for registering routes , widgets , importing scripts and setting the context
 */
class VariationCrossSellingProvider extends TemplateServiceProvider
{
    /**
     * default route register
     */
    public function register()
    {
        $this->getApplication()->register(VariationCrossSellingRouteServiceProvider::class);

        $this->getApplication()->bind(CrossSellerModelContract::class,CrossSellerModelRepository::class);
    }

    /**
     * Listens on io import to add scripts and set context
     * @param Twig $twig
     * @param Dispatcher $eventDispatcher
     */
    public function boot(Twig $twig, Dispatcher $eventDispatcher)
    {
        $eventDispatcher->listen(
            'IO.Resources.Import',
            function (ResourceContainer $resourceContainer)
            {
                $resourceContainer->addScriptTemplate('VariationCrossSelling::Content.Scripts');
                $resourceContainer->addStyleTemplate('VariationCrossSelling::Content.Styles');
            }
        );

//        $eventDispatcher->listen('IO.ctx.item', function (TemplateContainer $container)
//        {
//            $container->setContext(VariationCrossSellingContext::class);
//            return false;
//        }, 0);

        $twig->addExtension(VariationCrossSeller::class);
        $widgetRepository = pluginApp(ContentWidgetRepositoryContract::class);
        $widgetRepository->registerWidget(VariationCrossSellingWidget::class);
    }
}