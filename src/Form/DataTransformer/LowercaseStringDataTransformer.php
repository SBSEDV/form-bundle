<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<string, string>
 */
class LowercaseStringDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ?string $encoding = null
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): ?string
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): ?string
    {
        if (\is_string($value)) {
            return \mb_strtolower($value, $this->encoding);
        }

        return $value;
    }
}
