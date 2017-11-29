<?php

namespace Codefog\MemberExportBundle;

class ExportConfig
{
    /**
     * @var bool
     */
    private $hasHeaderFields = false;

    /**
     * @var bool
     */
    private $useRawData = false;

    /**
     * @return bool
     */
    public function hasHeaderFields()
    {
        return $this->hasHeaderFields;
    }

    /**
     * @param bool $hasHeaderFields
     */
    public function setHasHeaderFields($hasHeaderFields)
    {
        $this->hasHeaderFields = $hasHeaderFields;
    }

    /**
     * @return bool
     */
    public function useRawData()
    {
        return $this->useRawData;
    }

    /**
     * @param bool $useRawData
     */
    public function setUseRawData($useRawData)
    {
        $this->useRawData = $useRawData;
    }
}
