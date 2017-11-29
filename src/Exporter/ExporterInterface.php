<?php

namespace Codefog\MemberExportBundle\Exporter;

use Codefog\MemberExportBundle\ExportConfig;

interface ExporterInterface
{
    /**
     * Get the alias
     *
     * @return string
     */
    public function getAlias();

    /**
     * Run the export
     *
     * @param ExportConfig $config
     */
    public function export(ExportConfig $config);
}
