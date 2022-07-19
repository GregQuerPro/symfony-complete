<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtension extends AbstractExtension {

    public function getFilters()
    {
        return [
            new TwigFilter('default_image', [$this, 'defaultImage'])
        ];
    }

    public function defaultImage(string $path): string {
        if(strlen(trim($path))) {
            return 'as.jpg';
        }
        return $path;
    }
}