<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\CauseResolver\CauseResolverInterface;
use SBSEDV\Bundle\FormBundle\CauseResolver\ChainCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\ConstraintViolationCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\CsrfTokenCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\DoctrineTypeCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\InvalidChoiceCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\StringableCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\StringCauseResolver;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ChainCauseResolver::class)
            ->arg('$causeResolvers', tagged_iterator('sbsedv_form.cause_resolver'))

        ->alias(CauseResolverInterface::class, ChainCauseResolver::class)

        ->set(StringCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -999])

        ->set(StringableCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -9999])

        ->set(InvalidChoiceCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -100])

        ->set(CsrfTokenCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -100])

        ->set(ConstraintViolationCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -100])

        ->set(DoctrineTypeCauseResolver::class)
            ->tag('sbsedv_form.cause_resolver', ['priority' => -100])
    ;
};
