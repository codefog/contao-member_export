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
     * Generate a form to choose the export method
     * @return string
     */
    public function generate()
    {
        if (\Input::get('key') != 'export')
        {
            return '';
        }

        // Export records
        if (\Input::post('FORM_SUBMIT') == 'tl_member_export')
        {
            $varCallback = $GLOBALS['MEMBER_EXPORT_FORMATS'][\Input::post('format')];

            if (!$varCallback)
            {
                $this->reload();
            }

            try
            {
                if (is_array($varCallback))
                {
                    \System::importStatic($varCallback[0])->{$varCallback[1]}(\Input::post('headerFields'), \Input::post('raw'));
                }
                elseif (is_callable($varCallback))
                {
                    $varCallback(\Input::post('headerFields'), \Input::post('raw'));
                }
            }
            catch (\Exception $e)
            {
                \Message::addError($e->getMessage());
            }

            $this->reload();
        }

        $strOptions = '';

        // Generate options
        foreach (array_keys($GLOBALS['MEMBER_EXPORT_FORMATS']) as $format)
        {
            $strOptions .= '<option value="'.$format.'">'.$GLOBALS['TL_LANG']['tl_member']['export_format_ref'][$format].'</option>';
        }

        return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=export', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_member']['export'][1].'</h2>
<div class="tl_formbody_edit">'.$GLOBALS['TL_LANG']['tl_member']['export_description'].'</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_member_export" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_member_export">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox">
  <h3><label for="format">'.$GLOBALS['TL_LANG']['tl_member']['export_format'].'</label></h3>
  <select name="format" id="format" class="tl_select" onfocus="Backend.getScrollOffset()">
    '.$strOptions.'
  </select>

  <div class="tl_checkbox_single_container">
    <input type="checkbox" name="headerFields" id="headerFields" class="tl_checkbox" value="1" onfocus="Backend.getScrollOffset()">
    <label for="headerFields">'.$GLOBALS['TL_LANG']['tl_member']['export_headerFields'].'</label>
  </div>

  <div class="tl_checkbox_single_container">
    <input type="checkbox" name="raw" id="raw" class="tl_checkbox" value="1" onfocus="Backend.getScrollOffset()">
    <label for="raw">'.$GLOBALS['TL_LANG']['tl_member']['export_raw'].'</label>
  </div>
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="export" id="export" class="tl_submit" accesskey="e" value="'.specialchars($GLOBALS['TL_LANG']['tl_member']['export'][0]).'">
</div>

</div>
</form>';
    }


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
