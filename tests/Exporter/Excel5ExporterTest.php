<?php

namespace Codefog\MemberExportBundle\Tests\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Codefog\MemberExportBundle\Exporter\Excel5Exporter;
use Haste\IO\Writer\ExcelFileWriter;

class Excel5ExporterTest extends ExporterTestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(Excel5Exporter::class, new Excel5Exporter($this->mockFramework()));
    }

    public function testGetAlias()
    {
        $exporter = new Excel5Exporter($this->mockFramework());

        static::assertSame('excel5', $exporter->getAlias());
    }

    public function testExport()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, true, $exportComplete)
        );

        $exporter = new Excel5Exporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }

    public function testExportNoData()
    {
        $this->expectException(ExportException::class);

        $exporter = new Excel5Exporter($this->mockFramework($this->getExportNoDataAdapters()));
        $exporter->export($this->getExportConfig());
    }

    public function testExportNoFields()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, false, $exportComplete)
        );

        $exporter = new Excel5Exporter($framework);
        $exporter->export($this->getExportConfig());

        static::assertTrue($exportComplete);
    }
}
