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
    public function __construct(private readonly ConfigServiceInterface $configService)
    {
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('template_exists', $this->templateExists(...))];
    }

    /**
     * Checks if the template exists
     */
    public function templateExists($template)
    {
        $root = $this->configService->getContainerParameter('kernel.project_dir');

        return is_file($root . '/templates/' . $template);
    }
}
