<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

$GLOBALS['TL_LANG']['tl_member']['export'] = ['Export', 'Export members'];

/*
 * Reference
 */
$GLOBALS['TL_LANG']['tl_member']['export_description'] = 'Here you can export the members data to the file. Please use the below dropdown menu to choose the desired format. The download will begin immediately after you submit the form.';
$GLOBALS['TL_LANG']['tl_member']['export_format'] = ['Export format', 'Please choose the format of the export file.'];
$GLOBALS['TL_LANG']['tl_member']['export_considerFilters'] = ['Consider listing filters', 'Export only the rows that have been filtered in the listing view.'];
$GLOBALS['TL_LANG']['tl_member']['export_headerFields'] = ['Include header fields', 'Include the column headers as the first row.'];
$GLOBALS['TL_LANG']['tl_member']['export_raw'] = ['Raw data only', 'Export the data without formatting it.'];
$GLOBALS['TL_LANG']['tl_member']['export_formatRef'] = [
    'csv' => 'CSV (.csv)',
    'excel' => 'Excel (.xlsx)',
];
