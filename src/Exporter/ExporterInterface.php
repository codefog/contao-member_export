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
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;

#[AutoconfigureTag(self::TAG)]
interface ExporterInterface
{
    public const TAG = 'codefog_member_exporter';

    /**
     * Get the alias.
     */
    public static function getAlias(): string;

    /**
     * Return true if the exporter is available.
     */
    public function isAvailable(): bool;

    /**
     * Run the export and return the response.
     */
    public function export(ExportConfig $config): Response;
}
