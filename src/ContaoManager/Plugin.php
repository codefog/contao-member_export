<?php

namespace Codefog\MemberExportBundle\ContaoManager;

use Codefog\MemberExportBundle\CodefogMemberExportBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CodefogMemberExportBundle::class)->setLoadAfter([ContaoCoreBundle::class, 'haste']),
        ];
    }
}
