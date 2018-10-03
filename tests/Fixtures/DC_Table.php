<?php

namespace Codefog\MemberExportBundle\Fixtures;

class DC_Table
{
    protected $procedure = [];

    protected $values = [];

    public function __construct()
    {
        $this->procedure = isset($GLOBALS['DC_TABLE_PROCEDURE']) ? $GLOBALS['DC_TABLE_PROCEDURE'] : [];
        $this->values = isset($GLOBALS['DC_TABLE_VALUES']) ? $GLOBALS['DC_TABLE_VALUES'] : [];
    }

    protected function showAll()
    {
    }
}
