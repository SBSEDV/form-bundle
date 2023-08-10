<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\ParamResolver;

use Symfony\Component\Form\FormError;

/**
 * @example key
 * @example key.nested
 * @example key.nested.deeply
 */
class DotEncodingParamResolver implements ParamResolverInterface
{
    public function resolveParam(FormError $formError): ?string
    {
        $origin = $formError->getOrigin();

        // this SHOULD NEVER happen
        if (null === $origin) {
            return null;
        }

        $keys = [(string) $origin->getPropertyPath()];

        $parent = $origin->getParent();
        while ($parent !== null && $parent->getName() !== '') {
            $keys[] = (string) $parent->getPropertyPath();
            $parent = $parent->getParent();
        }

        $keys = \array_reverse($keys);
        foreach ($keys as $k => $v) {
            $keys[$k] = \str_replace(['[', ']'], '', $v);
        }

        $key = \implode('.', $keys);

        // @phpstan-ignore-next-line
        if ('' === $key) {
            return null;
        }

        return $key;
    }
}
