<?php

namespace Codefog\MemberExportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExporterPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * @var string
     */
    private $registry;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var array
     */
    private $excelServices = [];

    /**
     * ExporterPass constructor.
     *
     * @param string $registry
     * @param string $tag
     * @param array  $excelServices
     */
    public function __construct($registry, $tag, array $excelServices)
    {
        $this->registry = $registry;
        $this->tag = $tag;
        $this->excelServices = $excelServices;
    }

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->registry)) {
            return;
        }

        $definition = $container->getDefinition($this->registry);

        foreach ($this->findAndSortTaggedServices($this->tag, $container) as $service) {
            // Skip the Excel exported if the extension is not installed
            if (!class_exists('PHPExcel') && in_array((string) $service, $this->excelServices, true)) {
                continue;
            }

            $definition->addMethodCall('add', [$service]);
        }
    }
}
