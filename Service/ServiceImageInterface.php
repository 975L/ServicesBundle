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
 * Interface to be called for DI for ServiceImageInterface related services
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
interface ServiceImageInterface
{
    /**
     * Deletes picture file
     * @return ContactForm
     */
    public function delete(string $file);

    /**
     * Gets the images folder
     * @return string
     */
    public function getFolder(string $folder);

    /**
     * Resizes picture
     * @return bool
     */
    public function resize($file, string $folder, string $filename, string $format = 'jpg', int $finalHeight = 400, $compression = 75);
}
