<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle\Exporter;

use Codefog\MemberExportBundle\ExportConfig;

interface ExporterInterface
{
    /**
     * Get the alias.
     *
     * @return string
     */
    public function getAlias();

    /**
     * Run the export.
     *
     * @param ExportConfig $config
     */
    public function export(ExportConfig $config);
}
