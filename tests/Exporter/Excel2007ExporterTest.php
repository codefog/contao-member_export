<?php

namespace Codefog\MemberExportBundle\Tests\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Codefog\MemberExportBundle\Exporter\Excel2007Exporter;
use Haste\IO\Writer\ExcelFileWriter;

class Excel2007ExporterTest extends ExporterTestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(Excel2007Exporter::class, new Excel2007Exporter($this->mockFramework()));
    }

    public function testGetAlias()
    {
        $exporter = new Excel2007Exporter($this->mockFramework());

        static::assertSame('excel2007', $exporter->getAlias());
    }

    public function testExport()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, true, $exportComplete)
        );

        $exporter = new Excel2007Exporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }

    public function testExportNoData()
    {
        $this->expectException(ExportException::class);

        $exporter = new Excel2007Exporter($this->mockFramework($this->getExportNoDataAdapters()));
        $exporter->export($this->getExportConfig());
    }

    public function testExportNoFields()
    {
        $exportComplete = false;
;
        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, false, $exportComplete)
        );

        $exporter = new Excel2007Exporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }
}
