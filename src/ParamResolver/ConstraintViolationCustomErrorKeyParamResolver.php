<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\ParamResolver;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationCustomErrorKeyParamResolver implements ParamResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolveParam(FormError $formError): ?string
    {
        $cause = $formError->getCause();

        if ($cause instanceof ConstraintViolation) {
            $params = $cause->getParameters();
            if (\array_key_exists('custom_error_key', $params)) {
                return $params['custom_error_key'];
            }
        }

        return null;
    }
}
