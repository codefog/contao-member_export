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
     * ExporterPass constructor.
     *
     * @param string $registry
     * @param string $tag
     */
    public function __construct($registry, $tag)
    {
        $this->registry = $registry;
        $this->tag = $tag;
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
            $definition->addMethodCall('add', [$service]);
        }
    }
}
