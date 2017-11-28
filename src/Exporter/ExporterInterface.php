<?php

namespace Codefog\MemberExportBundle\Exporter;

interface ExporterInterface
{
    /**
     * Get the alias
     *
     * @return string
     */
    public function getAlias();
}
