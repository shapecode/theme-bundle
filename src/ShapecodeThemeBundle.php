<?php

namespace Shapecode\Bundle\ThemeBundle;

use Shapecode\Bundle\ThemeBundle\DependencyInjection\Compiler\TemplateResourcesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ShapecodeThemeBundle
 *
 * @package Shapecode\Bundle\ThemeBundle
 * @author  Nikita Loges
 * @company tenolo GbR
 */
class ShapecodeThemeBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplateResourcesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -10);
    }
}
