<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle\Exporter;

use Haste\IO\Writer\CsvFileWriter;

class CsvExporter extends BaseExporter
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'csv';
    }

    /**
     * {@inheritdoc}
     */
    protected function getWriter()
    {
        /** @var CsvFileWriter $writer */
        $writer = $this->framework->createInstance(CsvFileWriter::class);

        return $writer;
    }
}
