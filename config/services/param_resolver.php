<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\ParamResolver\ChainParamResolver;
use SBSEDV\Bundle\FormBundle\ParamResolver\ParamResolverInterface;
use SBSEDV\Bundle\FormBundle\ParamResolver\PropertyPathParamResolver;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ChainParamResolver::class)
            ->arg('$paramResolvers', tagged_iterator('sbsedv_form.param_resolver'))

        ->alias(ParamResolverInterface::class, ChainParamResolver::class)

        ->set(PropertyPathParamResolver::class)
            ->tag('sbsedv_form.param_resolver', ['priority' => -9999])
    ;
};
