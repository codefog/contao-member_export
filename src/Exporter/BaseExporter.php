<?php

/*
 * Member Export Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle\Exporter;

use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DcaLoader;
use Contao\File;
use Contao\FilesModel;
use Contao\MemberModel;
use Contao\Model\Collection;
use Contao\Validator;
use Haste\IO\Reader\ModelCollectionReader;
use Haste\IO\Writer\AbstractFileWriter;
use Haste\Util\Format;

abstract class BaseExporter implements ExporterInterface
{
    /**
     * @var ContaoFrameworkInterface
     */
    protected $framework;

    /**
     * BaseExporter constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * {@inheritdoc}
     */
    public function export(ExportConfig $config)
    {
        if (null === ($models = $this->getModels())) {
            throw new ExportException('There are no members to export');
        }

        /** @var ModelCollectionReader $reader */
        $reader = $this->framework->createInstance(ModelCollectionReader::class, [$models]);
        $writer = $this->getWriter();

        // Set the row callback
        $writer->setRowCallback($this->getRowCallback($config));

        // Set the header fields
        if ($config->hasHeaderFields()) {
            $reader->setHeaderFields($this->getHeaderFields($config));
            $writer->enableHeaderFields();
        }

        // Write the data
        $writer->writeFrom($reader);

        /** @var File $file */
        $file = $this->framework->createInstance(File::class, [$writer->getFilename()]);
        $file->sendToBrowser();
    }

    /**
     * Get the row callback.
     *
     * @param ExportConfig $config
     *
     * @return \Closure
     */
    protected function getRowCallback(ExportConfig $config)
    {
        /**
         * @var FilesModel
         * @var Format     $format
         * @var Validator  $validator
         */
        $filesModel = $this->framework->getAdapter(FilesModel::class);
        $format = $this->framework->getAdapter(Format::class);
        $validator = $this->framework->getAdapter(Validator::class);

        return function (array $row) use ($config, $filesModel, $format, $validator) {
            $return = [];

            foreach ($this->getFields() as $name => $data) {
                if ($config->useRawData()) {
                    $return[$name] = $row[$name];
                } else {
                    $return[$name] = $format->dcaValue('tl_member', $name, $row[$name]);

                    // Handle the UUIDs
                    if ($validator->isUuid($row[$name])) {
                        $return[$name] = $filesModel->findByPk($row[$name])->path;
                    }
                }
            }

            return \array_values($return);
        };
    }

    /**
     * Get the header fields.
     *
     * @param ExportConfig $config
     *
     * @return array
     */
    protected function getHeaderFields(ExportConfig $config)
    {
        $headerFields = [];

        foreach ($this->getFields() as $name => $data) {
            $headerFields[] = ($config->useRawData() || !$data['label'][0]) ? $name : $data['label'][0];
        }

        return $headerFields;
    }

    /**
     * Get the fields.
     *
     * @return array
     */
    protected function getFields()
    {
        /** @var DcaLoader $dcaLoader */
        $dcaLoader = $this->framework->createInstance(DcaLoader::class, ['tl_member']);
        $dcaLoader->load();

        if (!isset($GLOBALS['TL_DCA']['tl_member']['fields']) || !\is_array($GLOBALS['TL_DCA']['tl_member']['fields'])) {
            return [];
        }

        return $GLOBALS['TL_DCA']['tl_member']['fields'];
    }

    /**
     * Get the models.
     *
     * @return Collection|null
     */
    protected function getModels()
    {
        /** @var MemberModel $memberModel */
        $memberModel = $this->framework->getAdapter(MemberModel::class);

        return $memberModel->findAll();
    }

    /**
     * Get the writer.
     *
     * @return AbstractFileWriter
     */
    abstract protected function getWriter();
}
