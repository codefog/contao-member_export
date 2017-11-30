<?php

/*
 * Member Export Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

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
    private $excluded = [];

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
     * @param array $excluded
     */
    public function setExcluded(array $excluded)
    {
        $this->excluded = $excluded;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->registry)) {
            return;
        }

        $definition = $container->getDefinition($this->registry);

        foreach ($this->findAndSortTaggedServices($this->tag, $container) as $service) {
            if (\in_array((string) $service, $this->excluded, true)) {
                continue;
            }

            $definition->addMethodCall('add', [$service]);
        }
    }
}
