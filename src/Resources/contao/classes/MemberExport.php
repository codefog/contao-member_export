<?php

/**
 * member_export extension for Contao Open Source CMS
 *
 * Copyright (C) 2011-2014 Codefog
 *
 * @package member_export
 * @author  Codefog <http://codefog.pl>
 * @author  Kamil Kuzminski <kamil.kuzminski@codefog.pl>
 * @license LGPL
 */

namespace MemberExport;

/**
 * Class MemberExport
 *
 * Provides methods to handle member export.
 */
class MemberExport extends \Backend
{
    /**
     * Export the file
     * @param object
     * @param boolean
     * @param boolean
     */
    protected function exportFile(\Haste\IO\Writer\WriterInterface $objWriter, $blnHeaderFields, $blnRawData)
    {
        $objMembers = \MemberModel::findAll();

        // Reload if there are no members
        if ($objMembers === null)
        {
            $this->reload();
        }

        $objReader = new \Haste\IO\Reader\ModelCollectionReader($objMembers);

        // Set header fields
        if ($blnHeaderFields)
        {
            $arrHeaderFields = array();

            foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $strField => $arrField)
            {
                $arrHeaderFields[] = ($blnRawData || !$arrField['label'][0]) ? $strField : $arrField['label'][0];
            }

            $objReader->setHeaderFields($arrHeaderFields);
            $objWriter->enableHeaderFields();
        }

        // Format the values
        $objWriter->setRowCallback(function($arrRow) use ($blnRawData)
        {
            $arrReturn = [];

            foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $strField => $arrField) {
                if ($blnRawData) {
                    $arrReturn[$strField] = $arrRow[$strField];
                } else {
                    $arrReturn[$strField] = \Haste\Util\Format::dcaValue('tl_member', $strField, $arrRow[$strField]);

                    // Handle the UUIDs
                    if (\Validator::isUuid($arrRow[$strField])) {
                        $arrReturn[$strField] = \FilesModel::findByPk($arrRow[$strField])->path;
                    }
                }
            }

            return array_values($arrReturn);
        });

        $objWriter->writeFrom($objReader);

        $objFile = new \File($objWriter->getFilename());
        $objFile->sendToBrowser();
    }


    /**
     * Export records to CSV file
     * @param boolean
     * @param boolean
     */
    public function exportCsv($blnHeaderFields, $blnRawData)
    {
        $objWriter = new \Haste\IO\Writer\CsvFileWriter();
        $this->exportFile($objWriter, $blnHeaderFields, $blnRawData);
    }


    /**
     * Export records to Excel5 file
     * @param boolean
     * @param boolean
     */
    public function exportExcel5($blnHeaderFields, $blnRawData)
    {
        $objWriter = new \Haste\IO\Writer\ExcelFileWriter();
        $objWriter->setFormat('Excel5');
        $this->exportFile($objWriter, $blnHeaderFields, $blnRawData);
    }


    /**
     * Export records to Excel2007 file
     * @param boolean
     * @param boolean
     */
    public function exportExcel2007($blnHeaderFields, $blnRawData)
    {
        $objWriter = new \Haste\IO\Writer\ExcelFileWriter();
        $objWriter->setFormat('Excel2007');
        $this->exportFile($objWriter, $blnHeaderFields, $blnRawData);
    }
}
