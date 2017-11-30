<?php

namespace Codefog\MemberExportBundle\Exporter;

use Haste\IO\Writer\ExcelFileWriter;

class Excel2007Exporter extends BaseExporter
{
    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'excel2007';
    }

    /**
     * @inheritDoc
     */
    protected function getWriter()
    {
        /** @var ExcelFileWriter $writer */
        $writer = $this->framework->createInstance(ExcelFileWriter::class);
        $writer->setFormat('Excel2007');

        return $writer;
    }
}
