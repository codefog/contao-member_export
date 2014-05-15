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
 * Add a global operation to tl_member
 */
$GLOBALS['TL_DCA']['tl_member']['list']['global_operations']['export'] = array
(
    'label'               => &$GLOBALS['TL_LANG']['tl_member']['export'],
    'href'                => 'key=export',
    'icon'                => 'pickfile.gif',
    'attributes'          => 'onclick="Backend.getScrollOffset()"',
    'options'             => array('csv', 'excel5', 'excel2007'),
    'reference'           => &$GLOBALS['TL_LANG']['tl_member']['export'],
);
