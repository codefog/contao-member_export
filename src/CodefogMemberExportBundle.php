<?php

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\DependencyInjection\Compiler\ExporterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CodefogMemberExportBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ExporterPass(
            'codefog_member_export.registry',
            'codefog_member_export.exporter',
            ['codefog_member_export.exporter.excel5', 'codefog_member_export.exporter.excel2007']
        ));
    }
}
