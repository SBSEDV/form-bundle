<?php declare(strict_types=1);

namespace SBSEDV\Bundle\FormBundle\Form;

use Symfony\Component\Form\FormInterface;

interface FormProcessorInterface
{
    /**
     * Process the form by setting the form values to the given object.
     *
     * @param FormInterface $form        The form to process.
     * @param T             $object      The object to populate.
     * @param string[]      $ignoredKeys [optional] Form keys to ignore.
     *
     * @template T of object
     */
    public function processForm(FormInterface $form, object &$object, array $ignoredKeys = []): void;
}
