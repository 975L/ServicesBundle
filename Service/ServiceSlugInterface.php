<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

/**
 * Interface to be called for DI for ServiceSlugInterface related services
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
interface ServiceSlugInterface
{
    /**
     * Checks if slug already exists
     * @return bool
     */
    public function exists(string $object, string $slug);

    /**
     * Checks if the slug provided in the url matches the product one or will provide the redirect url
     * @return string|null
     */
    public function match(string $route, object $object, string $slug);

    /**
     * Checks unicity of slugged text against collection of $object ('Bundle:Entity')
     * @return string
     */
    public function slugify(string $object, string $text);
}
