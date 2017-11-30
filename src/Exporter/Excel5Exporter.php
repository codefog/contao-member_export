<?php

namespace Codefog\MemberExportBundle\Exporter;

use Haste\IO\Writer\ExcelFileWriter;

class Excel5Exporter extends BaseExporter
{
    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'excel5';
    }

    /**
     * @inheritDoc
     */
    protected function getWriter()
    {
        /** @var ExcelFileWriter $writer */
        $writer = $this->framework->createInstance(ExcelFileWriter::class);
        $writer->setFormat('Excel5');

        return $writer;
    }
}
