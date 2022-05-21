<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer as SymfonyUuidToStringTransformer;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

class UuidToStringDataTransformer extends SymfonyUuidToStringTransformer
{
    protected const NIL = '00000000-0000-0000-0000-000000000000';

    public function __construct(
        private bool $convertNilToNull = false
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function transform(mixed $value): ?string
    {
        $value = parent::transform($value);

        if ($this->convertNilToNull && $value === self::NIL) {
            return null;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(mixed $value): ?Uuid
    {
        $value = parent::reverseTransform($value);

        if ($value instanceof Uuid && (string) $value === self::NIL) {
            $value = $this->convertNilToNull ? null : new NilUuid();
        }

        return $value;
    }
}
