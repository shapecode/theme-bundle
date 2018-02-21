<?php

namespace Shapecode\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Bundle\AsseticBundle\DependencyInjection\DirectoryResourceDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This pass adds directory resources to scan for assetic assets.
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 */
class TemplateResourcesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // bundle and kernel resources
        $bundles = $container->getParameter('kernel.bundles');

        $asseticBundles = $container->getParameterBag()->resolveValue($container->getParameter('assetic.bundles'));

        foreach ($asseticBundles as $bundleName) {
            $rc = new \ReflectionClass($bundles[$bundleName]);
            $this->setBundleDirectoryResources($container, 'twig', dirname($rc->getFileName()), $bundleName);
        }
//
        $this->setAppDirectoryResources($container, 'twig');
    }

    protected function setBundleDirectoryResources(ContainerBuilder $container, $engine, $bundleDirName, $bundleName)
    {
        $definition = $container->getDefinition('assetic.'.$engine.'_directory_resource.'.$bundleName);
        $themes = $container->getParameter('shapecode_theme.themes');

        $resources = $definition->getArgument(0);

        foreach ($themes as $theme) {
            $resources[] = new DirectoryResourceDefinition(
                $bundleName,
                $engine,
                [
                    $container->getParameter('kernel.root_dir') . '/Resources/' . $bundleName . '/themes/' . $theme,
                    $bundleDirName . '/Resources/themes/' . $theme,
                ]
            );
        }

        $definition->setArgument(0, $resources);
    }

    protected function setAppDirectoryResources(ContainerBuilder $container, $engine)
    {
        $themes = $container->getParameter('shapecode_theme.themes');
        foreach ($themes as $key => $theme) {
            $themes[$key] = $container->getParameter('kernel.root_dir') . '/Resources/themes/' . $theme;
        }
        $themes[] = $container->getParameter('kernel.root_dir') . '/Resources/views';

        $container->setDefinition(
            'assetic.' . $engine . '_directory_resource.kernel',
            new DirectoryResourceDefinition('', $engine, $themes)
        );
    }
}
