<?php

namespace Codefog\MemberExportBundle\Exporter;

use Haste\IO\Writer\CsvFileWriter;

class CsvExporter extends BaseExporter
{
    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'csv';
    }

    /**
     * @inheritDoc
     */
    protected function getWriter()
    {
        /** @var CsvFileWriter $writer */
        $writer = $this->framework->createInstance(CsvFileWriter::class);

        return $writer;
    }
}
