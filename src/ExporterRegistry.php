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

class ExporterRegistry
{
    /**
     * @var array
     */
    private $exporters = [];

    /**
     * Add the exporter.
     *
     * @param ExporterInterface $exporter
     */
    public function add(ExporterInterface $exporter)
    {
        $this->exporters[$exporter->getAlias()] = $exporter;
    }

    /**
     * @param string $alias
     *
     * @throws \InvalidArgumentException
     *
     * @return ExporterInterface
     */
    public function get($alias)
    {
        if (!\array_key_exists($alias, $this->exporters)) {
            throw new \InvalidArgumentException(\sprintf('The exporter "%s" does not exist', $alias));
        }

        return $this->exporters[$alias];
    }

    /**
     * Get the aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return \array_keys($this->exporters);
    }
}
