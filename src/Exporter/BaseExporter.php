<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle\Exporter;

use Codefog\HasteBundle\Formatter;
use Codefog\MemberExportBundle\DataContainerHelper;
use Codefog\MemberExportBundle\Exception\ExportException;
use Codefog\MemberExportBundle\ExportConfig;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DcaLoader;
use Contao\FilesModel;
use Contao\MemberModel;
use Contao\Model\Collection;
use Contao\StringUtil;
use Contao\Validator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

abstract class BaseExporter implements ExporterInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Filesystem $fs,
        private readonly Formatter $formatter,
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function export(ExportConfig $config): Response
    {
        if (null === ($models = $this->getModels($config))) {
            throw new ExportException('There are no members to export');
        }

        $fileName = sprintf('member-export-%s.%s', date('Y-m-d-H-i-s'), $this->getFileExtension());

        $response = new BinaryFileResponse($this->getExportFile($config, $models));
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        return $response;
    }

    /**
     * Get the file extension.
     */
    abstract protected function getFileExtension(): string;

    /**
     * Get the export file.
     */
    abstract protected function getExportFile(ExportConfig $config, Collection $models): \SplFileInfo;

    /**
     * Create the temporary file.
     */
    protected function createTemporaryFile(string $content = null): \SplFileInfo
    {
        $file = new \SplFileInfo(tempnam(sys_get_temp_dir(), 'contao-member-export-'));

        if ($content !== null) {
            $this->fs->dumpFile($file->getPathname(), $content);
        }

        return $file;
    }

    /**
     * Get the row callback.
     */
    protected function getRowCallback(ExportConfig $config): \closure
    {
        /**
         * @var FilesModel $filesModel
         * @var StringUtil $stringUtil
         * @var Validator  $validator
         */
        $filesModel = $this->framework->getAdapter(FilesModel::class);
        $stringUtil = $this->framework->getAdapter(StringUtil::class);
        $validator = $this->framework->getAdapter(Validator::class);

        return function (array $row) use ($config, $filesModel, $stringUtil, $validator) {
            // @codeCoverageIgnoreStart
            $return = [];

            foreach ($this->getFields() as $name => $data) {
                if ($config->useRawData()) {
                    $return[$name] = $row[$name];
                } else {
                    // Prevent the date fields from being exported with current date due to an empty value
                    if (!isset($row[$name]) || $row[$name] === '') {
                        $return[$name] = '';
                    } else {
                        $return[$name] = $this->formatter->dcaValue('tl_member', $name, $row[$name]);
                    }

                    // Handle the UUIDs
                    if ($validator->isUuid($row[$name])) {
                        $return[$name] = $filesModel->findByPk($row[$name])->path;
                    }
                }

                $return[$name] = $stringUtil->decodeEntities($return[$name]);
            }

            return \array_values($return);
            // @codeCoverageIgnoreEnd
        };
    }

    /**
     * Get the header fields.
     */
    protected function getHeaderFields(ExportConfig $config): array
    {
        $headerFields = [];

        foreach ($this->getFields() as $name => $data) {
            $headerFields[] = ($config->useRawData() || !$data['label'][0]) ? $name : $data['label'][0];
        }

        return $headerFields;
    }

    /**
     * Get the fields.
     */
    protected function getFields(): array
    {
        /** @var DcaLoader $dcaLoader */
        $dcaLoader = $this->framework->createInstance(DcaLoader::class, ['tl_member']);
        $dcaLoader->load();

        if (!isset($GLOBALS['TL_DCA']['tl_member']['fields']) || !\is_array($GLOBALS['TL_DCA']['tl_member']['fields'])) {
            return [];
        }

        $fields = [];

        foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $name => $data) {
            // Skip the excluded fields
            if (isset($data['eval']['memberExportExcluded']) && $data['eval']['memberExportExcluded']) {
                continue;
            }

            $fields[$name] = $data;
        }

        return $fields;
    }

    /**
     * Get the models.
     */
    protected function getModels(ExportConfig $config = null): ?Collection
    {
        /** @var MemberModel $memberModel */
        $memberModel = $this->framework->getAdapter(MemberModel::class);

        // Return all members if no config was provided (BC) or we should not consider listing filters
        if ($config === null || !$config->getConsiderFilters()) {
            return $memberModel->findAll();
        }

        $helper = new DataContainerHelper('tl_member');
        $helper->buildProceduresAndValues();

        // Return all members if there are no filters set
        if (count($columns = $helper->getProcedure()) === 0) {
            return $memberModel->findAll();
        }

        return $memberModel->findBy($columns, $helper->getValues());
    }
}
