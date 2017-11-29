<?php


namespace Codefog\MemberExportBundle\Test\DependencyInjection;

use Codefog\MemberExportBundle\DependencyInjection\CodefogMemberExportExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CodefogTagsExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new CodefogMemberExportExtension();
        $extension->load([], $container);

        $this->assertTrue($container->hasDefinition('codefog_member_export.controller'));
        $this->assertTrue($container->hasDefinition('codefog_member_export.registry'));
        $this->assertTrue($container->hasDefinition('codefog_member_export.exporter.csv'));
        $this->assertTrue($container->hasDefinition('codefog_member_export.exporter.excel5'));
        $this->assertTrue($container->hasDefinition('codefog_member_export.exporter.excel2007'));
    }
}
