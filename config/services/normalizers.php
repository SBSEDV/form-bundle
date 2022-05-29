<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\CauseResolver\CauseResolverInterface;
use SBSEDV\Bundle\FormBundle\ParamResolver\ParamResolverInterface;
use SBSEDV\Bundle\FormBundle\Serializer\Normalizer\FormErrorNormalizer;
use SBSEDV\Bundle\FormBundle\Serializer\Normalizer\UnsubmittedFormNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(UnsubmittedFormNormalizer::class)
            ->args([
                '$translator' => service(TranslatorInterface::class),
            ])
            ->tag('serializer.normalizer')

        ->set(FormErrorNormalizer::class)
            ->args([
                '$causeResolver' => service(CauseResolverInterface::class),
                '$paramResolver' => service(ParamResolverInterface::class),
            ])
            ->tag('serializer.normalizer', ['priority' => 0])
    ;
};
