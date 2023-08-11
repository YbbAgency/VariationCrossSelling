<?php

namespace VariationCrossSelling\Extensions;

use Plenty\Plugin\Log\Loggable;
use Plenty\Plugin\Templates\Extensions\Twig_Extension;
use Plenty\Plugin\Templates\Factories\TwigFactory;
use VariationCrossSelling\Repositories\CrossSellerModelRepository;
use VariationCrossSelling\Repositories\frontendRepository;
use VariationCrossSelling\Repositories\uiRepository;

class VariationCrossSeller extends Twig_Extension
{
    use Loggable;

    private $factory;

    /** default class constructor
     * @param TwigFactory $factory
     */
    public function __construct(TwigFactory $factory)
    {
        $this->factory = $factory;
    }

    /** Extension for getting cross seller with their data for frontend
     * @param $varId
     * @param $lang
     * @return array
     */
    public function getCrossSeller($varId,$lang)
    {
        $frontendRepository = pluginApp(frontendRepository::class);
        $crossSellerModelRepository = pluginApp(CrossSellerModelRepository::class);

        $crossSellerModelByVariationId = $crossSellerModelRepository->getCrossSellerModelByVariationId($varId);

        $crossSeller = [];

        for($i = 0 ; $i < sizeof($crossSellerModelByVariationId->crossSeller) ; $i++)
        {
            $data = $frontendRepository->getVarDataByIdFrontend($crossSellerModelByVariationId->crossSeller[$i]["varId"],$lang);
            $data["variation"]["documents"][0]["position"] = $crossSellerModelByVariationId->crossSeller[$i]["position"];
            $crossSeller[] =  $data["variation"]["documents"][0];
        }

        usort($crossSeller, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        return $crossSeller;
    }

    public function getFunctions():array
    {
        return
            [
                $this->factory->createSimpleFunction('getCrossSeller', [$this, 'getCrossSeller'])
            ];
    }

    public function getName():string
    {
        return "VariationCrossSeller";
    }
}