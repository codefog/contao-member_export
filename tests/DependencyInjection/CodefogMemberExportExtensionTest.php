<?php


namespace Codefog\MemberExportBundle\Test\DependencyInjection;

use Codefog\MemberExportBundle\DependencyInjection\CodefogMemberExportExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CodefogMemberExportExtensionTest extends TestCase
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

        $this->assertTrue($container->getDefinition('codefog_member_export.controller')->isPublic());
        $this->assertFalse($container->getDefinition('codefog_member_export.registry')->isPublic());
        $this->assertFalse($container->getDefinition('codefog_member_export.exporter.csv')->isPublic());
        $this->assertFalse($container->getDefinition('codefog_member_export.exporter.excel5')->isPublic());
        $this->assertFalse($container->getDefinition('codefog_member_export.exporter.excel2007')->isPublic());
    }
}
