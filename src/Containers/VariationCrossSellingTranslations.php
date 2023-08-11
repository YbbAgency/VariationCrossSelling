<?php

namespace VariationCrossSelling\Containers;

use Plenty\Plugin\Templates\Twig;

class VariationCrossSellingTranslations
{
    /**
     * Renders translation file
     * @param Twig $twig
     * @return string
     */
  public function call(Twig $twig):string
  {
      return $twig->render('VariationCrossSelling::LoadTranslations');
  }
}