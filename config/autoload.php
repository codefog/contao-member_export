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
 * Register the namespace
 */
ClassLoader::addNamespace('MemberExport');


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'MemberExport\MemberExport' => 'system/modules/member_export/classes/MemberExport.php'
));
