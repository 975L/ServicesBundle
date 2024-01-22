<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Services related to ServiceToolsInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ServiceTools implements ServiceToolsInterface
{
    /**
     * Stores current Request
     */
    private readonly ?\Symfony\Component\HttpFoundation\Request $request;

    public function __construct(
        RequestStack $requestStack,
        /**
         * Stores Translator
         */
        private readonly TranslatorInterface $translator,
        /**
         * Stores Router
         */
        private readonly RouterInterface $router
    )
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function createFlash(string $text, string $translationDomain = null, string $style = 'success', array $options = [])
    {
        if (null !== $this->request) {
            if (null !== $translationDomain) {
                $text = $this->translator->trans($text, $options, $translationDomain);
            }

            $this->request
                ->getSession()
                ->getFlashBag()
                ->add($style, $text)
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(string $data)
    {
        $url = null;

        //Calculates the url if a Route is provided
        if (str_contains($data, ',')) {
            $routeData = $this->getUrlFromRoute($data);
            $url = $this->router->generate($routeData['route'], $routeData['params'], UrlGeneratorInterface::ABSOLUTE_URL);
        //An url has been provided
        } elseif (str_contains($data, 'http')) {
            $url = $data;
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlFromRoute(string $route)
    {
        //Gets Route
        $routeValue = trim(substr($route, 0, strpos($route, ',')), "\'\"");

        //Gets parameters
        $params = trim(substr($route, strpos($route, '{')), "{}");
        $params = str_replace(['"', "'"], '', $params);
        $params = explode(',', $params);
        $paramsArray = [];
        foreach($params as $value) {
            $parameter = explode(':', $value);
            $paramsArray[trim($parameter[0])] = trim($parameter[1]);
        }

        return ['route' => $routeValue, 'params' => $paramsArray];
    }
}
