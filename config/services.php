<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autoconfigure()
        ->autowire()
    ;

    $services
        ->load('Codefog\\MemberExportBundle\\', __DIR__ . '/../src')
        ->exclude(__DIR__ . '/../src/Exception')
        ->exclude(__DIR__ . '/../src/DataContainerHelper.php')
        ->exclude(__DIR__ . '/../src/ExportConfig.php')
    ;

    $services->set(\Codefog\MemberExportBundle\ExportController::class)->public();
};
