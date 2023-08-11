<?php

namespace VariationCrossSelling\Widgets;

use Ceres\Widgets\Helper\BaseWidget;
use Ceres\Widgets\Helper\Factories\WidgetDataFactory;
use Ceres\Widgets\Helper\Factories\WidgetSettingsFactory;
use Ceres\Widgets\Helper\WidgetTypes;

/**
 * default widget registration in category item type
 */
class VariationCrossSellingWidget extends BaseWidget
{
    public $template = "VariationCrossSelling::Widget.VariationCrossSelling";

    /**
     * default getData function with type of category
     * @return array
     */
    public function getData()
    {
        return WidgetDataFactory::make("VariationCrossSelling::VariationCrossSelling")
            ->withLabel("Widget.VariationCrossSelling")
            ->withType(WidgetTypes::DEFAULT)
            ->withCategory('VariationCrossSelling')
            ->withPosition(1000)
            ->toArray();
    }

    /**
     * default getSettings function with a wrapper class
     * @return array
     */
    public function getSettings()
    {
        /** @var WidgetSettingsFactory $settingsFactory */
        $settingsFactory = pluginApp(WidgetSettingsFactory::class);

        $settingsFactory->createCustomClass();

        $settingsFactory->createCheckbox("showHeadline")
            ->withDefaultValue(true)
            ->withName("Widget.showHeadline");

        $settingsFactory->createCheckbox("roundedBtns")
            ->withDefaultValue(false)
            ->withName("Widget.roundedBtns");

        return $settingsFactory->toArray();
    }
}

