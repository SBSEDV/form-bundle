<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\ParamResolver;

use Symfony\Component\Form\FormError;

class ChainParamResolver implements ParamResolverInterface
{
    /**
     * @param iterable<ParamResolverInterface> $paramResolvers
     */
    public function __construct(
        private readonly iterable $paramResolvers,
    ) {
    }

    public function resolveParam(FormError $formError): ?string
    {
        foreach ($this->paramResolvers as $paramResolver) {
            $param = $paramResolver->resolveParam($formError);

            if (null !== $param) {
                return $param;
            }
        }

        return null;
    }
}
