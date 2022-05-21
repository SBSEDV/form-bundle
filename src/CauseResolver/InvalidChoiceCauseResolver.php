<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;

class InvalidChoiceCauseResolver implements CauseResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveCause(FormError $formError): ?string
    {
        $cause = $formError->getCause();

        if ($cause instanceof ConstraintViolation && $cause->getCode() === Form::NOT_SYNCHRONIZED_ERROR) {
            $innerType = $formError->getOrigin()?->getConfig()->getType()->getInnerType();

            if ($innerType instanceof ChoiceType || $innerType instanceof EnumType) {
                return 'invalid_choice';
            }
        }

        return null;
    }
}
