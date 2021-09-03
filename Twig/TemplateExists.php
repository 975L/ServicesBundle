<?php
/*
 * (c) 2019: 975L <contact@975l.com>
 * (c) 2019: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Twig;

use c975L\ConfigBundle\Service\ConfigServiceInterface;
use Symfony\Component\HttpKernel\Kernel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateExists extends AbstractExtension
{
    private $configService;

    public function __construct(ConfigServiceInterface $configService)
    {
        $this->configService = $configService;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('template_exists', array($this, 'templateExists')),
        );
    }

    /**
     * Checks if the template exists
     */
    public function templateExists($template)
    {
        $templatesFolder = '3' === substr(Kernel::VERSION, 0, 1) ? '/app/Resources/views/' : '/../templates/';
        $templatesFolder = $this->configService->getContainerParameter('kernel.project_dir') . $templatesFolder;

        return is_file($templatesFolder . $template);
    }
}
