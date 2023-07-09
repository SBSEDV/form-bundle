<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\MessageResolver\ChainMessageResolver;
use SBSEDV\Bundle\FormBundle\MessageResolver\MessageResolverInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ChainMessageResolver::class)
            ->arg('$causeResolvers', tagged_iterator('sbsedv_form.message_resolver'))

        ->alias(MessageResolverInterface::class, ChainMessageResolver::class)
    ;
};
