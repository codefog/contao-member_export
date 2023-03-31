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
use Symfony\Component\HttpFoundation\Response;

interface ExporterInterface
{
    public const TAG = 'codefog_member_exporter';

    /**
     * Get the alias.
     */
    public static function getAlias(): string;

    /**
     * Run the export and return the response.
     */
    public function export(ExportConfig $config): Response;
}
