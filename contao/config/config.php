<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

$GLOBALS['BE_MOD']['accounts']['member']['export'] = [\Codefog\MemberExportBundle\ExportController::class, 'run'];
