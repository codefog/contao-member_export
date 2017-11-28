<?php

namespace Codefog\MemberExportBundle\Exporter;

class CsvExporter implements ExporterInterface
{
    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'csv';
    }
}
