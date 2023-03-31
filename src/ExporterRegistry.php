<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\Exporter\ExporterInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ExporterRegistry
{
    /**
     * @var iterable<ExporterInterface>
     */
    private iterable $exporters;

    public function __construct(
        #[TaggedIterator(ExporterInterface::TAG, defaultIndexMethod: 'getAlias')] iterable $exporters,
    )
    {
        $this->exporters = $exporters;
    }

    public function get(string $alias): ExporterInterface
    {
        foreach ($this->exporters as $exporter) {
            if ($exporter->getAlias() === $alias) {
                return $exporter;
            }
        }

        throw new \InvalidArgumentException(\sprintf('The exporter "%s" does not exist', $alias));
    }

    public function getAliases(): array
    {
        $aliases = [];

        foreach ($this->exporters as $exporter) {
            $aliases[] = $exporter->getAlias();
        }

        return $aliases;
    }
}
