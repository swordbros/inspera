<?php
namespace Swordbros\Base\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BaseTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('customFunction', [$this, 'customFunction']),
        ];
    }

    public function customFunction($param)
    {
        // İşlevin gerçekleştireceği işlemleri burada tanımlayın
        return 'Özel işlev çalıştı! Parametre: ' . $param;
    }
}
