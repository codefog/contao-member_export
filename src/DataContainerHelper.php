<?php

declare(strict_types=1);

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2026, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Contao\DC_Table;

class DataContainerHelper extends DC_Table
{
    /**
     * Build the procedures and values.
     */
    public function buildProceduresAndValues(): void
    {
        $this->showAll();
    }

    /**
     * Get the procedure.
     *
     * @return array
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Get the values.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
