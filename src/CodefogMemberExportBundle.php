<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\DependencyInjection\Compiler\ExporterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CodefogMemberExportBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $exporterPass = new ExporterPass('codefog_member_export.registry', 'codefog_member_export.exporter');
        $this->handleExcelServices($exporterPass);
        $container->addCompilerPass($exporterPass);
    }

    /**
     * Exclude the Excel services if the required class is not found.
     *
     * @param ExporterPass $exporterPass
     *
     * @codeCoverageIgnore
     */
    private function handleExcelServices(ExporterPass $exporterPass)
    {
        if (!\class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $exporterPass->setExcluded(['codefog_member_export.exporter.excel5', 'codefog_member_export.exporter.excel2007']);
        }
    }
}
