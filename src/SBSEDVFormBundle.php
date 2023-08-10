<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SBSEDVFormBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services/cause_resolver.php');
        $container->import('../config/services/form_types.php');
        $container->import('../config/services/form_processor.php');
        $container->import('../config/services/message_resolver.php');
        $container->import('../config/services/normalizers.php');
        $container->import('../config/services/param_resolver.php');

        $builder
            ->registerForAutoconfiguration(MessageResolver\MessageResolverInterface::class)
            ->addTag('sbsedv_form.message_resolver')
        ;
        if (!$config['message_resolver']['doctrine_type']) {
            $container->services()->remove(MessageResolver\DoctrineTypeMessageResolver::class);
        }

        $builder
            ->registerForAutoconfiguration(CauseResolver\CauseResolverInterface::class)
            ->addTag('sbsedv_form.cause_resolver')
        ;

        if (!$config['cause_resolver']['string']) {
            $container->services()->remove(CauseResolver\StringCauseResolver::class);
        }

        if (!$config['cause_resolver']['stringable']) {
            $container->services()->remove(CauseResolver\StringableCauseResolver::class);
        }

        if (!$config['cause_resolver']['invalid_choice']) {
            $container->services()->remove(CauseResolver\InvalidChoiceCauseResolver::class);
        }

        if (!$config['cause_resolver']['csrf_token']) {
            $container->services()->remove(CauseResolver\CsrfTokenCauseResolver::class);
        }

        if (!$config['cause_resolver']['doctrine_type']) {
            $container->services()->remove(CauseResolver\DoctrineTypeCauseResolver::class);
        }

        if (!$config['cause_resolver']['constraint_violation']) {
            $container->services()->remove(CauseResolver\ConstraintViolationCauseResolver::class);
        }

        $builder
            ->registerForAutoconfiguration(ParamResolver\ParamResolverInterface::class)
            ->addTag('sbsedv_form.param_resolver')
        ;

        if (!$config['param_resolver']['property_path']) {
            $container->services()->remove(ParamResolver\PropertyPathParamResolver::class);
        }

        if (!$config['param_resolver']['dot_encoding']) {
            $container->services()->remove(ParamResolver\DotEncodingParamResolver::class);
        }
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }
}
