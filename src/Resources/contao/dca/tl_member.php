<?php

/*
 * Member Export Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

array_insert($GLOBALS['TL_DCA']['tl_member']['list']['global_operations'], 0, [
    'export' => [
        'label' => &$GLOBALS['TL_LANG']['tl_member']['export'],
        'href' => 'key=export',
        'icon' => 'pickfile.svg',
        'attributes' => 'onclick="Backend.getScrollOffset()"',
    ],
]);
