<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\Type;

use SBSEDV\Bundle\FormBundle\Form\DataTransformer\BooleanTypeToBooleanDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BooleanType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new BooleanTypeToBooleanDataTransformer(
                $options['true_values'], // @phpstan-ignore-line
                $options['false_values'], // @phpstan-ignore-line
                $options['default_value'] // @phpstan-ignore-line
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => false,
            'true_values' => ['true', true, 'yes', 'y', 'on', '1', 1],
            'false_values' => ['false', false, 'no', 'n', 'off', '0', 0],
            'default_value' => false,
            'invalid_message' => $this->translator->trans('invalid_boolean_string', domain: 'sbsedv_form'),
        ]);

        $resolver->setAllowedTypes('default_value', ['bool', 'null']);
    }
}
