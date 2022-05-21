<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\DependencyInjection;

use SBSEDV\Bundle\FormBundle\CauseResolver\CauseResolverInterface;
use SBSEDV\Bundle\FormBundle\CauseResolver\ChainCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\ConstraintViolationCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\CsrfTokenCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\InvalidChoiceCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\StringableCauseResolver;
use SBSEDV\Bundle\FormBundle\CauseResolver\StringCauseResolver;
use SBSEDV\Bundle\FormBundle\Form\Extension\UuidTypeExtension;
use SBSEDV\Bundle\FormBundle\Form\FormProcessor;
use SBSEDV\Bundle\FormBundle\Form\FormProcessorInterface;
use SBSEDV\Bundle\FormBundle\Form\Type\BooleanType;
use SBSEDV\Bundle\FormBundle\ParamResolver\ChainParamResolver;
use SBSEDV\Bundle\FormBundle\ParamResolver\ParamResolverInterface;
use SBSEDV\Bundle\FormBundle\ParamResolver\PropertyPathParamResolver;
use SBSEDV\Bundle\FormBundle\Serializer\Normalizer\FormErrorNormalizer;
use SBSEDV\Bundle\FormBundle\Serializer\Normalizer\UnsubmittedFormNormalizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SBSEDVFormExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->registerCauseResolvers($container, $config);
        $this->registerParamResolvers($container, $config);
        $this->registerNormalizers($container, $config);
        $this->registerFormTypes($container, $config);

        $container
            ->setDefinition(FormProcessor::class, new Definition(FormProcessor::class))
            ->setArguments([
                '$propertyAccessor' => new Reference(PropertyAccessorInterface::class, ContainerInterface::NULL_ON_INVALID_REFERENCE),
            ])
        ;
        $container->setAlias(FormProcessorInterface::class, FormProcessor::class);
    }

    /**
     * Register the custom form types.
     */
    private function registerFormTypes(ContainerBuilder $container, array $config): void
    {
        $container
            ->setDefinition(BooleanType::class, new Definition(BooleanType::class))
            ->setArguments([
                '$translator' => new Reference(TranslatorInterface::class),
            ])
            ->addTag('form.type')
        ;

        $container
            ->setDefinition(UuidTypeExtension::class, new Definition(UuidTypeExtension::class))
            ->addTag('form.type_extension')
        ;
    }

    /**
     * Register the \CauseResolver\ components.
     */
    private function registerCauseResolvers(ContainerBuilder $container, array $config): void
    {
        $container
            ->registerForAutoconfiguration(CauseResolverInterface::class)
            ->addTag('sbsedv_form.cause_resolver')
        ;

        $container
            ->setDefinition(ChainCauseResolver::class, new Definition(ChainCauseResolver::class))
            ->setArgument('$causeResolvers', tagged_iterator('sbsedv_form.cause_resolver'))
        ;

        $container->setAlias(CauseResolverInterface::class, ChainCauseResolver::class);

        if ($config['cause_resolver']['string'] === true) {
            $container
                ->setDefinition(StringCauseResolver::class, new Definition(StringCauseResolver::class))
                ->addTag('sbsedv_form.cause_resolver', ['priority' => -999])
            ;
        }

        if ($config['cause_resolver']['stringable'] === true) {
            $container
                ->setDefinition(StringableCauseResolver::class, new Definition(StringableCauseResolver::class))
                ->addTag('sbsedv_form.cause_resolver', ['priority' => -9999])
            ;
        }

        if ($config['cause_resolver']['invalid_choice'] === true) {
            $container
                ->setDefinition(InvalidChoiceCauseResolver::class, new Definition(InvalidChoiceCauseResolver::class))
                ->addTag('sbsedv_form.cause_resolver', ['priority' => -100])
            ;
        }

        if ($config['cause_resolver']['csrf_token'] === true) {
            $container
                ->setDefinition(CsrfTokenCauseResolver::class, new Definition(CsrfTokenCauseResolver::class))
                ->addTag('sbsedv_form.cause_resolver', ['priority' => -100])
            ;
        }

        if ($config['cause_resolver']['constraint_violation'] === true) {
            $container
                ->setDefinition(ConstraintViolationCauseResolver::class, new Definition(ConstraintViolationCauseResolver::class))
                ->addTag('sbsedv_form.cause_resolver', ['priority' => -100])
            ;
        }
    }

    /**
     * Register the \ParamResolver\ components.
     */
    private function registerParamResolvers(ContainerBuilder $container, array $config): void
    {
        $container
            ->registerForAutoconfiguration(ParamResolverInterface::class)
            ->addTag('sbsedv_form.param_resolver')
        ;

        $container
            ->setDefinition(ChainParamResolver::class, new Definition(ChainParamResolver::class))
            ->setArgument('$paramResolvers', tagged_iterator('sbsedv_form.param_resolver'))
        ;

        $container->setAlias(ParamResolverInterface::class, ChainParamResolver::class);

        if ($config['param_resolver']['property_path'] === true) {
            $container
                ->setDefinition(PropertyPathParamResolver::class, new Definition(PropertyPathParamResolver::class))
                ->addTag('sbsedv_form.param_resolver', ['priority' => -9999])
            ;
        }
    }

    /**
     * Register the "serializer.normalizer" services.
     */
    private function registerNormalizers(ContainerBuilder $container, array $config): void
    {
        $container
            ->setDefinition(UnsubmittedFormNormalizer::class, new Definition(UnsubmittedFormNormalizer::class))
            ->setArguments([
                '$translator' => new Reference(TranslatorInterface::class),
            ])
            ->addTag('serializer.normalizer')
        ;

        $container
            ->setDefinition(FormErrorNormalizer::class, new Definition(FormErrorNormalizer::class))
            ->setArguments([
                '$causeResolver' => new Reference(CauseResolverInterface::class),
                '$paramResolver' => new Reference(ParamResolverInterface::class),
            ])
            ->addTag('serializer.normalizer', ['priority' => 0])
        ;
    }
}
