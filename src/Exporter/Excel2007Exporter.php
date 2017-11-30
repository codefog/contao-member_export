<?php

/*
 * Member Export Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle\Exporter;

use Haste\IO\Writer\ExcelFileWriter;

class Excel2007Exporter extends BaseExporter
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'excel2007';
    }

    /**
     * {@inheritdoc}
     */
    protected function getWriter()
    {
        /** @var ExcelFileWriter $writer */
        $writer = $this->framework->createInstance(ExcelFileWriter::class);
        $writer->setFormat('Excel2007');

        return $writer;
    }
}
