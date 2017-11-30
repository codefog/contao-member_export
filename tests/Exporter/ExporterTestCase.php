<?php

namespace Codefog\MemberExportBundle\Tests\Exporter;

use Codefog\MemberExportBundle\ExportConfig;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DcaLoader;
use Contao\File;
use Contao\FilesModel;
use Contao\MemberModel;
use Contao\Validator;
use Haste\IO\Reader\ModelCollectionReader;
use Haste\Util\Format;
use PHPUnit\Framework\TestCase;

abstract class ExporterTestCase extends TestCase
{
    /**
     * @return ExportConfig
     */
    protected function getExportConfig()
    {
        $config = new ExportConfig();
        $config->setHasHeaderFields(true);

        return $config;
    }

    /**
     * @return array
     */
    protected function getExportNoDataAdapters()
    {
        $model = $this->createPartialMock(Adapter::class, ['findAll']);
        $model
            ->method('findAll')
            ->willReturn(null)
        ;

        return [MemberModel::class => $model];
    }

    /**
     * @return array
     */
    protected function getExportAdapters()
    {
        $model = $this->createPartialMock(Adapter::class, ['findAll']);
        $model
            ->method('findAll')
            ->willReturn([])
        ;

        return [MemberModel::class => $model];
    }

    /**
     * @param string $writerClass
     * @param bool   $hasFields
     * @param bool   $exportComplete
     *
     * @return array
     */
    protected function getExportInstances($writerClass, $hasFields, &$exportComplete)
    {
        $dcaLoader = $this->createPartialMock(Adapter::class, ['load']);
        $dcaLoader
            ->method('load')
            ->willReturnCallback(function () use ($hasFields) {
                if ($hasFields) {
                    $GLOBALS['TL_DCA']['tl_member']['fields'] = [
                        'firstname' => ['label' => 'First name'],
                        'lastname' => ['label' => 'Lastname'],
                    ];
                } else {
                    unset($GLOBALS['TL_DCA']['tl_member']);
                }
            })
        ;

        $file = $this->createPartialMock(Adapter::class, ['sendToBrowser']);
        $file
            ->method('sendToBrowser')
            ->willReturnCallback(function () use (&$exportComplete) {
                $exportComplete = true;
            })
        ;

        $reader = $this->createMock(ModelCollectionReader::class);
        $writer = $this->createMock($writerClass);

        return [
            DcaLoader::class => $dcaLoader,
            File::class => $file,
            ModelCollectionReader::class => $reader,
            $writerClass => $writer,
        ];
    }

    /**
     * @param array $adapters
     * @param array $instances
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ContaoFrameworkInterface
     */
    protected function mockFramework(array $adapters = [], array $instances = [])
    {
        $filesModel = $this->createPartialMock(Adapter::class, ['findByPk']);
        $filesModel
            ->method('findByPk')
            ->willReturnCallback(function () {
                $helper = new \stdClass();
                $helper->path = 'path/to/file.jpg';

                return $helper;
            })
        ;

        $format = $this->createPartialMock(Adapter::class, ['dcaValue']);
        $format
            ->method('dcaValue')
            ->willReturn('foobar')
        ;

        $validator = $this->createPartialMock(Adapter::class, ['isUuid']);
        $validator
            ->method('isUuid')
            ->willReturn(true)
        ;

        $adapters = array_merge([
            FilesModel::class => $filesModel,
            Format::class => $format,
            Validator::class => $validator,
        ], $adapters);

        $framework = $this->createMock(ContaoFrameworkInterface::class);

        $framework
            ->method('createInstance')
            ->willReturnCallback(function ($key) use ($instances) {
                return $instances[$key];
            })
        ;

        $framework
            ->method('getAdapter')
            ->willReturnCallback(function ($key) use ($adapters) {
                return $adapters[$key];
            })
        ;

        return $framework;
    }
}
