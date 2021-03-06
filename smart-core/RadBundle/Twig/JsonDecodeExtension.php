<?php

namespace SmartCore\RadBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class JsonDecodeExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'twig.json_decode';
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFilters()
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('json_decode', [$this, 'jsonDecode']),
        ];
    }

    /**
     * @param string $string
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     *
     * @return mixed
     */
    public function jsonDecode($string, $assoc = false, $depth = 512, $options = 0)
    {
        return json_decode($string, $assoc, $depth, $options);
    }
}
