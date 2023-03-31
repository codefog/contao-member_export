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
use Contao\MemberModel;
use Contao\Model\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: 48)]
class ExcelExporter extends BaseExporter
{
    public static function getAlias(): string
    {
        return 'excel';
    }

    protected function getFileExtension(): string
    {
        return 'xlsx';
    }

    protected function getExportFile(ExportConfig $config, Collection $models): \SplFileInfo
    {
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();

        $rowIndex = 0;

        // Add the header fields
        if ($config->hasHeaderFields()) {
            foreach ($this->getHeaderFields($config) as $index => $value) {
                $activeSheet->setCellValue([$index, $rowIndex], $value);
            }

            $rowIndex++;
        }

        $rowCallback = $this->getRowCallback($config);

        /** @var MemberModel $model */
        foreach ($models as $model) {
            foreach (array_values($rowCallback($model->row())) as $index => $value) {
                $activeSheet->setCellValue([$index, $rowIndex], $value);
            }

            $rowIndex++;
        }

        $file = $this->createTemporaryFile();

        $writer = new Xlsx($spreadsheet);
        $writer->save($file->getPathname());

        return $file;
    }
}
