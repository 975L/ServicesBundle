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
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Services related to ServiceToolsInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ServiceTools implements ServiceToolsInterface
{
    /**
     * Stores current Request
     * @var Request
     */
    private $request;

    /**
     * Stores Router
     * @var RouterInterface
     */
    private $router;

    /**
     * Stores Translator
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        RouterInterface $router
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function createFlash(string $translationDomain = null, string $text, string $style = 'success', array $options = array())
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
        if (false !== strpos($data, ',')) {
            $routeData = $this->getUrlFromRoute($data);
            $url = $this->router->generate($routeData['route'], $routeData['params'], UrlGeneratorInterface::ABSOLUTE_URL);
        //An url has been provided
        } elseif (false !== strpos($data, 'http')) {
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
        $params = str_replace(array('"', "'"), '', $params);
        $params = explode(',', $params);
        $paramsArray = array();
        foreach($params as $value) {
            $parameter = explode(':', $value);
            $paramsArray[trim($parameter[0])] = trim($parameter[1]);
        }

        return array(
            'route' => $routeValue,
            'params' => $paramsArray
        );
    }
}
