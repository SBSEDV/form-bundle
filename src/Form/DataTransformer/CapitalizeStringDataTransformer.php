<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements DataTransformerInterface<string, string>
 */
class CapitalizeStringDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ?string $encoding = null
    ) {
    }

    public function transform(mixed $value): ?string
    {
        return $value;
    }

    public function reverseTransform(mixed $value): ?string
    {
        if (null === $value) {
            return $value;
        }

        if (!\is_string($value)) {
            throw new TransformationFailedException('Expected type string.');
        }

        return $this->mb_ucfirst($value);
    }

    private function mb_ucfirst(string $string): string
    {
        $firstChar = \mb_substr($string, 0, 1, $this->encoding);
        $lastChars = \mb_substr($string, 1, null, $this->encoding);

        return \mb_strtoupper($firstChar, $this->encoding).$lastChars;
    }
}
