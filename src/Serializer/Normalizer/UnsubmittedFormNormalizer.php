<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Serializer\Normalizer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UnsubmittedFormNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /** The value will be used as the normalized "message" key (e.g. "msg"). */
    public const CONTEXT_MESSAGE_KEY = 'form_error.message_key';

    /** The value that will appear under the "type" key. */
    public const CONTEXT_ERROR_TYPE = 'form_error.type';

    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @param FormInterface $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [[
            $context[self::CONTEXT_MESSAGE_KEY] ?? 'message' => $this->translator->trans('request_body_is_empty', domain: 'sbsedv_form'),
            'type' => $context[self::CONTEXT_ERROR_TYPE] ?? 'invalid_request_error',
        ]];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof FormInterface && !$data->isSubmitted();
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
