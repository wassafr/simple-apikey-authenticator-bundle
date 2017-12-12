<?php

namespace Wassa\SimpleApiKeyAuthenticatorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WassaSimpleApiKeyAuthenticatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('wassa_simple_api_key_authenticator.api_key', $config['api_key']);
        $container->setParameter('wassa_simple_api_key_authenticator.default_action', $config['default_action']);
        $container->setParameter('wassa_simple_api_key_authenticator.order', $config['order']);
        $container->setParameter('wassa_simple_api_key_authenticator.secured_patterns', $config['secured_patterns']);
        $container->setParameter('wassa_simple_api_key_authenticator.unsecured_patterns', $config['unsecured_patterns']);
    }
}
