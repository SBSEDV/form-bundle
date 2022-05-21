<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sbsedv_form');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('cause_resolver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('constraint_violation')->defaultTrue()->end()
                        ->booleanNode('csrf_token')->defaultTrue()->end()
                        ->booleanNode('invalid_choice')->defaultTrue()->end()
                        ->booleanNode('string')->defaultTrue()->end()
                        ->booleanNode('stringable')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('param_resolver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('property_path')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
