<?php

namespace Shapecode\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeThemeExtension
 *
 * @package Shapecode\Bundle\ThemeBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodeThemeExtension extends ConfigurableExtension
{
    /**
     * @inheritdoc
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        foreach (array('themes', 'active_theme', 'path_patterns') as $key) {
            $container->setParameter($this->getAlias() . '.' . $key, $config[$key]);
        }

        $options = null;
        if (!empty($config['cookie']['name'])) {
            $options = array();
            foreach (array('name', 'lifetime', 'path', 'domain', 'secure', 'http_only') as $key) {
                $options[$key] = $config['cookie'][$key];
            }
        }
        $container->setParameter($this->getAlias() . '.cookie', $options);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
