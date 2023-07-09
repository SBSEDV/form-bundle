<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<string, string>
 */
class UppercaseStringDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly ?string $encoding = null
    ) {
    }

    public function transform(mixed $value): ?string
    {
        return $value;
    }

    public function reverseTransform(mixed $value): ?string
    {
        if (\is_string($value)) {
            return \mb_strtoupper($value, $this->encoding);
        }

        return $value;
    }
}
