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
use League\Csv\Writer;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(priority: 96)]
class CsvExporter extends BaseExporter
{
    public static function getAlias(): string
    {
        return 'csv';
    }

    public function isAvailable(): bool
    {
        return class_exists(Writer::class);
    }

    protected function getFileExtension(): string
    {
        return 'csv';
    }

    protected function getExportFile(ExportConfig $config, Collection $models): \SplFileInfo
    {
        $csv = Writer::createFromString();

        if ($config->hasHeaderFields()) {
            $csv->insertOne($this->getHeaderFields($config));
        }

        $rowCallback = $this->getRowCallback($config);

        /** @var MemberModel $model */
        foreach ($models as $model) {
            $csv->insertOne($rowCallback($model->row()));
        }

        return $this->createTemporaryFile($csv->toString());
    }
}
