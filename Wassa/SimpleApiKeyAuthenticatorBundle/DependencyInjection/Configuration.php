<?php

namespace Wassa\SimpleApiKeyAuthenticatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wassa_simple_api_key_authenticator');

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->info('API key')
                    ->defaultNull()
                ->end()
                ->enumNode('default_action')
                    ->info('Behaviour when no patterns are configured, when a URL doesn\'t match any pattern or when no API key is defined')
                    ->cannotBeEmpty()
                    ->values(array('check', 'dont_check'))
                    ->defaultValue('dont_check')
                ->end()
                ->enumNode('order')
                    ->info('Order to follow to check patterns')
                    ->cannotBeEmpty()
                    ->values(array('secured,unsecured', 'unsecured,secured'))
                    ->defaultValue('unsecured,secured')
                ->end()
                ->arrayNode('secured_patterns')
                    ->info('URL patterns for which the API key will be checked')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('unsecured_patterns')
                    ->info('URL patterns for which the API key will NOT be checked')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
