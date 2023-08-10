<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\ParamResolver;

use Symfony\Component\Form\FormError;

class PropertyPathParamResolver implements ParamResolverInterface
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

        $key = \str_replace(['[', ']'], '', $keys[0]);
        unset($keys[0]);

        foreach ($keys as $k) {
            if (!\str_starts_with($k, '[')) {
                $k = '['.$k;
            }

            if (!\str_ends_with($k, ']')) {
                $k .= ']';
            }

            $key .= $k;
        }

        if ($key === '') {
            return null;
        }

        return $key;
    }
}
