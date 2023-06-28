<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\Extension;

use SBSEDV\Bundle\FormBundle\Form\DataTransformer\StringToUuidDataTransformer;
use SBSEDV\Bundle\FormBundle\Form\DataTransformer\UuidToStringDataTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UuidTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->resetViewTransformers();

        if ($options['as_string']) {
            $builder
                ->addViewTransformer(new StringToUuidDataTransformer())
            ;
        }

        $builder
            ->addViewTransformer(new UuidToStringDataTransformer($options['nil_to_null'])) // @phpstan-ignore-line
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Whether to convert the special NIL uuid to null
            'nil_to_null' => false,
            'as_string' => false,
        ]);

        $resolver->setAllowedTypes('nil_to_null', 'bool');
        $resolver->setAllowedTypes('as_string', 'bool');
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            UuidType::class,
        ];
    }
}
