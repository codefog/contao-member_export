<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2026, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

use Codefog\MemberExportBundle\ExportController;

$GLOBALS['BE_MOD']['accounts']['member']['export'] = [ExportController::class, 'run'];
