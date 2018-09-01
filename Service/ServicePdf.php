<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

use Knp\Snappy\Pdf;
use Symfony\Component\Translation\TranslatorInterface;
use c975L\ServicesBundle\Service\ServiceToolsInterface;
use c975L\ServicesBundle\Service\ServicePdfInterface;

/**
 * Services related to ServicePdfInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ServicePdf implements ServicePdfInterface
{
    /**
     * Stores knpSnappyPdf
     * @var Pdf
     */
    private $knpSnappyPdf;

    /**
     * Stores ServiceToolsInterface
     * @var ServiceToolsInterface
     */
    private $serviceTools;

    /**
     * Stores TranslatorInterface
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        Pdf $knpSnappyPdf,
        ServiceToolsInterface $serviceTools,
        TranslatorInterface $translator
    )
    {
        $this->knpSnappyPdf = $knpSnappyPdf;
        $this->serviceTools = $serviceTools;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function html2Pdf(string $filename, string $html)
    {
        $content = $this->knpSnappyPdf->getOutputFromHtml($html);

        return array($content, $filename, 'application/pdf');
    }

    /**
     * {@inheritdoc}
     */
    public function getPdfFile(string $filename, string $url)
    {
        $content = file_get_contents($this->serviceTools->getUrl($url));
        $filename = $this->translator->trans($filename, array(), 'services') . '.pdf';

        return array($content, $filename, 'application/pdf');
    }
}
