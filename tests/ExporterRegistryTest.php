<?php

namespace Codefog\MemberExportBundle\Tests;

use Codefog\MemberExportBundle\Exporter\ExporterInterface;
use Codefog\MemberExportBundle\ExporterRegistry;
use PHPUnit\Framework\TestCase;

class ExporterRegistryTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(ExporterRegistry::class, new ExporterRegistry());
    }

    public function testAddProviders()
    {
        $exporter1 = $this->createMock(ExporterInterface::class);
        $exporter1
            ->method('getAlias')
            ->willReturn('exporter1')
        ;

        $exporter2 = $this->createMock(ExporterInterface::class);
        $exporter2
            ->method('getAlias')
            ->willReturn('exporter2')
        ;

        $registry = new ExporterRegistry();
        $registry->add($exporter1);
        $registry->add($exporter2);

        $this->assertSame($exporter1, $registry->get('exporter1'));
        $this->assertSame($exporter2, $registry->get('exporter2'));
        $this->assertSame(['exporter1', 'exporter2'], $registry->getAliases());
    }

    public function testProviderDoesNotExist()
    {
        $this->expectException(\InvalidArgumentException::class);

        $registry = new ExporterRegistry();
        $registry->get('foobar');
    }
}
