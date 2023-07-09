<?php declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

return function (DefinitionConfigurator $definitionConfigurator): void {
    $definitionConfigurator
        ->rootNode()
            ->children()
                ->arrayNode('cause_resolver')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('constraint_violation')->defaultTrue()->end()
                        ->booleanNode('csrf_token')->defaultTrue()->end()
                        ->booleanNode('doctrine_type')->defaultTrue()->end()
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
        ->end()
    ;
};
