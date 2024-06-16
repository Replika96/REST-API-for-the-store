<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MathExtension extends AbstractExtension
{
    public function getFunction() : array
    {
        return [
            new TwigFunction('round', function($a) {
                return round($a);
            })
        ];
    }
}