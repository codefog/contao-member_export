<?php

namespace Codefog\MemberExportBundle\Tests;

use Codefog\MemberExportBundle\ExportController;
use Codefog\MemberExportBundle\ExporterRegistry;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class ExportControllerTest extends TestCase
{
    public function testInstantiation()
    {
        $framework = $this->createMock(ContaoFrameworkInterface::class);
        $registry = $this->createMock(ExporterRegistry::class);
        $requestStack = $this->createMock(RequestStack::class);

        $this->assertInstanceOf(ExportController::class, new ExportController($framework, $registry, $requestStack));
    }
}
