<?php

/**
 * Add a global operation
 */
array_insert($GLOBALS['TL_DCA']['tl_member']['list']['global_operations'], 0, [
    'export' => [
        'label' => &$GLOBALS['TL_LANG']['tl_member']['export'],
        'href' => 'key=export',
        'icon' => 'pickfile.svg',
        'attributes' => 'onclick="Backend.getScrollOffset()"',
    ]
]);
