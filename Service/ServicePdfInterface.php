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
 * Interface to be called for DI for ServicePdfInterface related services
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
interface ServicePdfInterface
{
    /**
     * Returns an array($content, $filename, 'application/pdf') for a pdf file
     * @return array
     */
    public function getPdfFile(string $filename, string $url);

    /**
     * Returns an array($filepath, $filename, 'application/pdf') for a pdf file
     * @return array
     */
    public function getPdfFilePath(string $filename, string $url);

    /**
     * Converts html code to pdf and returns an array($content, $filename, 'application/pdf') of the converted pdf file
     * @return array
     */
    public function html2Pdf(string $filename, string $html);
}
