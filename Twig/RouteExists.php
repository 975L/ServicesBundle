<?php
/*
 * (c) 2019: 975L <contact@975l.com>
 * (c) 2019: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExists extends AbstractExtension
{
    public function __construct(
        /**
         * Stores Router
         */
        private readonly RouterInterface $router
    )
    {
    }

    public function getFunctions()
    {
        return [new TwigFunction('route_exists', $this->routeExists(...))];
    }

    public function routeExists($route)
    {
        return ($this->router->getRouteCollection()->get($route));
    }
}