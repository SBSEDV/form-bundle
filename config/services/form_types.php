<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\Form\Extension\UuidTypeExtension;
use SBSEDV\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(BooleanType::class)
            ->args([
                '$translator' => service(TranslatorInterface::class),
            ])
            ->tag('form.type')

        ->set(UuidTypeExtension::class)
            ->tag('form.type_extension')
    ;
};
