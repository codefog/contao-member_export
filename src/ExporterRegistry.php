<?php

declare(strict_types=1);

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2026, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\Exporter\ExporterInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ExporterRegistry
{
    public function __construct(
        /**
         * @var iterable<ExporterInterface>
         */
        #[TaggedIterator(ExporterInterface::TAG, defaultIndexMethod: 'getAlias')] private readonly iterable $exporters,
    ) {
    }

    public function get(string $alias): ExporterInterface
    {
        foreach ($this->exporters as $exporter) {
            if ($exporter->isAvailable() && $exporter->getAlias() === $alias) {
                return $exporter;
            }
        }

        throw new \InvalidArgumentException(\sprintf('The exporter "%s" does not exist', $alias));
    }

    public function getAliases(): array
    {
        $aliases = [];

        foreach ($this->exporters as $exporter) {
            if ($exporter->isAvailable()) {
                $aliases[] = $exporter->getAlias();
            }
        }

        return $aliases;
    }
}
