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
     * Deletes image file
     */
    public function delete(string $file);

    /**
     * Gets the images folder
     * @return string
     */
    public function getFolder(string $folder);

    /**
     * Resizes image
     * @return bool
     */
    public function resize($file, string $folder, string $filename, string $format = 'jpg', int $finalHeight = 400, int $compression = 75, bool $square = false, $stamp = null);

    /**
     * Rotates the image
     */
    public function rotate($file, $degree);

}
