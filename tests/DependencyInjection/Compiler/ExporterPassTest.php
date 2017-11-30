<?php

namespace Codefog\MemberExportBundle\Test\DependencyInjection;

use Codefog\MemberExportBundle\DependencyInjection\Compiler\ExporterPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ExporterPassTest extends TestCase
{
    /**
     * @var ExporterPass
     */
    private $exporterPass;

    public function setUp()
    {
        $this->exporterPass = new ExporterPass('registry', 'tag');
        $this->exporterPass->setExcluded(['exporter3']);
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(ExporterPass::class, $this->exporterPass);
    }

    public function testProcess()
    {
        $registryDefinition = new Definition();

        $exporterDefinition = new Definition();
        $exporterDefinition->addTag('tag', ['priority' => 32]);

        $exporterDefinition2 = new Definition();
        $exporterDefinition2->addTag('tag', ['priority' => 64]);

        $exporterDefinition3 = new Definition();
        $exporterDefinition3->addTag('tag', ['priority' => 128]);

        $container = new ContainerBuilder();
        $container->addDefinitions([
            'registry' => $registryDefinition,
            'exporter1' => $exporterDefinition,
            'exporter2' => $exporterDefinition2,
            'exporter3' => $exporterDefinition3,
        ]);

        $this->exporterPass->process($container);

        $calls = $registryDefinition->getMethodCalls();

        $this->assertEquals('add', $calls[0][0]);
        $this->assertInstanceOf(Reference::class, $calls[0][1][0]);
        $this->assertEquals('exporter2', (string) $calls[0][1][0]);
        $this->assertEquals('exporter1', (string) $calls[1][1][0]);
        $this->assertCount(2, $calls[0]);
    }

    public function testRegistryNotExists()
    {
        $container = new ContainerBuilder();
        $this->exporterPass->process($container);

        $this->assertFalse($container->hasDefinition('registry'));
    }
}
