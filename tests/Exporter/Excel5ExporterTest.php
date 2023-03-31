<?php

namespace Codefog\MemberExportBundle\Tests\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Codefog\MemberExportBundle\Exporter\ExcelExporter;
use Haste\IO\Writer\ExcelFileWriter;

class Excel5ExporterTest extends ExporterTestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(ExcelExporter::class, new ExcelExporter($this->mockFramework()));
    }

    public function testGetAlias()
    {
        $exporter = new ExcelExporter($this->mockFramework());

        $this->assertSame('excel5', $exporter->getAlias());
    }

    public function testExport()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, true, $exportComplete)
        );

        $exporter = new ExcelExporter($framework);
        $exporter->export($this->getExportConfig());
        $exporter->export($this->getExportConfig(false));

        $GLOBALS['DC_TABLE_PROCEDURE'] = ['foo'];
        $GLOBALS['DC_TABLE_VALUES'] = ['bar'];
        $exporter->export($this->getExportConfig());

        $this->assertTrue($exportComplete);
    }

    public function testExportNoData()
    {
        $this->expectException(ExportException::class);

        $exporter = new ExcelExporter($this->mockFramework($this->getExportNoDataAdapters()));
        $exporter->export($this->getExportConfig());
    }

    public function testExportNoFields()
    {
        $exportComplete = false;

        $framework = $this->mockFramework(
            $this->getExportAdapters(),
            $this->getExportInstances(ExcelFileWriter::class, false, $exportComplete)
        );

        $exporter = new ExcelExporter($framework);
        $exporter->export($this->getExportConfig());

        $this->assertTrue($exportComplete);
    }
}
