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


/**
 * Extension version
 */
@define('MEMBER_EXPORT_VERSION', '1.0');
@define('MEMBER_EXPORT_BUILD', '3');


/**
 * Add a new action to the member module
 */
$GLOBALS['BE_MOD']['accounts']['member']['export'] = array('MemberExport\MemberExport', 'generate');


/**
 * Member export options
 */
$GLOBALS['MEMBER_EXPORT_FORMATS'] = array
(
    'csv'       => array('MemberExport\MemberExport', 'exportCsv'),
    'excel5'    => array('MemberExport\MemberExport', 'exportExcel5'),
    'excel2007' => array('MemberExport\MemberExport', 'exportExcel2007'),
);
