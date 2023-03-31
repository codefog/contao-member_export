<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CodefogMemberExportBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }
}
