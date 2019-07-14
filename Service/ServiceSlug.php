<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Services related to ServiceSlugInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2017 975L <contact@975l.com>
 */
class ServiceSlug implements ServiceSlugInterface
{
    /**
     * Stores EntityManager
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Stores Router
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router
    )
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $object, string $slug)
    {
        $objects = $this->em
            ->getRepository($object)
            ->findAll()
        ;

        foreach ($objects as $object) {
            if ($object->getSlug() == $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $route, object $object, string $slug)
    {
        if ($slug !== $object->getSlug()) {
            return
                $this->router->generate($route, array(
                    'slug' => $object->getSlug(),
                    'id' => $object->getId(),
            ));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function slugify(string $object, string $text)
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify($text);

        //Checks unicity of slug or adds incremented number
        $finalSlug = $slug;
        $slugExists = true;
        $i = 1;
        do {
            $slugExists = $this->exists($object, $finalSlug);
            if ($slugExists) {
                $finalSlug = $slug . '-' . $i++;
            }
        } while (false !== $slugExists);

        return $finalSlug;
    }
}
