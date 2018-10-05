<?php

namespace Codefog\MemberExportBundle;

use Contao\DC_Table;

class DataContainerHelper extends DC_Table
{
    /**
     * Build the procedures and values
     */
    public function buildProceduresAndValues()
    {
        $this->showAll();
    }

    /**
     * Get the procedure
     *
     * @return array
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Get the values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
