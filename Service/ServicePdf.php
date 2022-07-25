<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Spipu\Html2Pdf\Html2Pdf;
use Mpdf\Mpdf;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Services related to ServicePdfInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2018 975L <contact@975l.com>
 */
class ServicePdf implements ServicePdfInterface
{
    public function __construct(
        /**
         * Stores ServiceToolsInterface
         */
        private readonly ServiceToolsInterface $serviceTools,
        /**
         * Stores TranslatorInterface
         */
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getPdfFile(string $filename, string $url)
    {
        $content = file_get_contents($this->serviceTools->getUrl($url));
        $filename = $this->translator->trans($filename, [], 'services') . '.pdf';

        return [$content, $filename, 'application/pdf'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPdfFilePath(string $filename, string $url)
    {
        $filePath = $this->serviceTools->getUrl($url);
        $filename = $this->translator->trans($filename, [], 'services') . '.pdf';

        return [$filePath, $filename, 'application/pdf'];
    }

    /**
     * {@inheritdoc}
     */
    public function html2Pdf(string $filename, string $html)
    {
$dompdf = null;
echo $html;
/*        $options = new Options();
        $options->setDpi(150);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();*/

// In this case, we want to write the file in the public directory
$publicDirectory = __DIR__.'/../../../../cotemassages.com/public';
// e.g /var/www/project/public/mypdf.pdf
$pdfFilepath =  $publicDirectory . '/mypdf.pdf';

// Write file to the desired path
//file_put_contents($pdfFilepath, $dompdf->output());


//$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf = new Mpdf([]);
dump($mpdf);
dump('here');die;
$mpdf->WriteHTML($html);
$mpdf->Output($pdfFilepath);

/*$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($html);
$html2pdf->output($pdfFilepath);*/




dump('here');die;






        return [$dompdf->output(), $filename, 'application/pdf'];
    }
}
