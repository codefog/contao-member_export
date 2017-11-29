<?php

namespace Codefog\TagsBundle\Test;

use Codefog\MemberExportBundle\ExportConfig;
use PHPUnit\Framework\TestCase;

class ExportConfigTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $config = new ExportConfig();
        $config->setHasHeaderFields(true);
        $config->setUseRawData(true);

        static::assertTrue($config->hasHeaderFields());
        static::assertTrue($config->useRawData());
    }
}
