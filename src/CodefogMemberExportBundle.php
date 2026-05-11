<?php

declare(strict_types=1);

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2026, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CodefogMemberExportBundle extends AbstractBundle
{
    /**
     * @param array<string, mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $services = $configurator->services();

        $services->defaults()
            ->autoconfigure()
            ->autowire()
        ;

        $services
            ->load('Codefog\\MemberExportBundle\\', __DIR__.'/../src')
            ->exclude(__DIR__.'/../src/Exception')
            ->exclude(__DIR__.'/../src/DataContainerHelper.php')
            ->exclude(__DIR__.'/../src/ExportConfig.php')
        ;

        $services->set(ExportController::class)->public();
    }
}
