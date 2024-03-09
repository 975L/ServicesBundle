<?php
/*
 * (c) 2024: 975L <contact@975l.com>
 * (c) 2024: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Twig;

use c975L\ConfigBundle\Service\ConfigServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExists extends AbstractExtension
{
    public function __construct(private readonly ConfigServiceInterface $configService)
    {
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('asset_exists', $this->assetExists(...))];
    }

    /**
     * Checks if the template exists
     */
    public function assetExists($asset)
    {
        $root = $this->configService->getContainerParameter('kernel.project_dir');

        return is_file($root . '/public/' . $asset);
    }
}
