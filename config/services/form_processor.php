<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\FormBundle\Form\FormProcessor;
use SBSEDV\Bundle\FormBundle\Form\FormProcessorInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(FormProcessor::class)
            ->args([
                '$propertyAccessor' => service(PropertyAccessorInterface::class)->nullOnInvalid(),
            ])

        ->alias(FormProcessorInterface::class, FormProcessor::class)
    ;
};
