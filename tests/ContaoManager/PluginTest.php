<?php

namespace Codefog\MemberExportBundle\Test\ContaoManager;

use Codefog\MemberExportBundle\CodefogMemberExportBundle;
use Codefog\MemberExportBundle\ContaoManager\Plugin;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(Plugin::class, new Plugin());
    }

    public function testGetBundles()
    {
        $plugin = new Plugin();
        $bundles = $plugin->getBundles($this->createMock(ParserInterface::class));

        /** @var BundleConfig $config */
        $config = $bundles[0];

        static::assertCount(1, $bundles);
        static::assertInstanceOf(BundleConfig::class, $config);
        static::assertEquals(CodefogMemberExportBundle::class, $config->getName());
        static::assertEquals([ContaoCoreBundle::class, 'haste'], $config->getLoadAfter());
    }
}
