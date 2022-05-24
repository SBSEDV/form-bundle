<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\CauseResolver;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationCauseResolver implements CauseResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveCause(FormError $formError): ?string
    {
        $cause = $formError->getCause();

        if (!$cause instanceof ConstraintViolation) {
            return null;
        }

        $constraint = $cause->getConstraint();
        $code = $cause->getCode();
        if (null === $constraint || null === $code) {
            return null;
        }

        try {
            return $constraint->getErrorName($code);
        } catch (\InvalidArgumentException) {
            return $code;
        }
    }
}
