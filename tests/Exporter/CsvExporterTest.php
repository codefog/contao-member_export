<?php

namespace Codefog\TagsBundle\Test\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Codefog\MemberExportBundle\Exporter\CsvExporter;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\MemberModel;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(CsvExporter::class, new CsvExporter($this->mockFramework()));
    }

    public function testGetAlias()
    {
        $exporter = new CsvExporter($this->mockFramework());

        static::assertSame('csv', $exporter->getAlias());
    }

    public function testExportNoData()
    {
        $model = $this->createPartialMock(Adapter::class, ['findAll']);
        $model
            ->method('findAll')
            ->willReturn(null)
        ;

        $exporter = new CsvExporter($this->mockFramework([MemberModel::class => $model]));

        $this->expectException(ExportException::class);
        $exporter->export(new ExportConfig());
    }

    protected function mockFramework(array $adapters = [])
    {
        $framework = $this->createMock(ContaoFrameworkInterface::class);

        $framework
            ->method('getAdapter')
            ->willReturnCallback(function ($key) use ($adapters) {
                return $adapters[$key];
            })
        ;

        return $framework;
    }
}
