<?php

namespace Codefog\MemberExportBundle\Test;

use Codefog\MemberExportBundle\CodefogMemberExportBundle;
use Codefog\MemberExportBundle\DependencyInjection\Compiler\ExporterPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CodefogMemberExportBundleTest extends TestCase
{
    public function testInstantiation()
    {
        static::assertInstanceOf(CodefogMemberExportBundle::class, new CodefogMemberExportBundle());
    }

    public function testRegisterCompilerPass()
    {
        $container = new ContainerBuilder();

        $bundle = new CodefogMemberExportBundle();
        $bundle->build($container);

        $found = false;

        foreach ($container->getCompiler()->getPassConfig()->getPasses() as $pass) {
            if ($pass instanceof ExporterPass) {
                $found = true;
                break;
            }
        }

        static::assertTrue($found);
    }
}
