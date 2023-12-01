[![PHPStan](https://github.com/SBSEDV/form-bundle/actions/workflows/phpstan.yml/badge.svg)](https://github.com/SBSEDV/form-bundle/actions/workflows/phpstan.yml)
[![PHPCS-Fixer](https://github.com/SBSEDV/form-bundle/actions/workflows/phpcsfixer.yml/badge.svg)](https://github.com/SBSEDV/form-bundle/actions/workflows/phpcsfixer.yml)

# sbsedv/form-bundle

A [Symfony](https://symfony.com/) bundle that adds some usefull [symfony/form](https://github.com/symfony/form) integrations.

---

## **Error Normalizer**

This bundle registers two [symfony/serializer](https://github.com/symfony/serializer) normalizers for form errors.

#### [**FormErrorNormalizer**](./src/Serializer/Normalizer/FormErrorNormalizer.php)

The most important normalizer. This normalizer supports submitted, invalid form.

The normalized data is an associative array with the following structure:

```json
[
    {
        // Each FormError object has its own entry
        "message": "The FormError object message",
        "type": "invalid_request_error",
        "param": "first_name", // OPTIONAL
        "cause": "is_blank_error" // OPTIONAL
    }
]
```

The `param` key will contain, by default, the property path of the child form that the FormError originates from. This key will not exist if the error originates from the root form (e.g. invalid CSRF Token).

The "cause" key is intended to contain an error name based on the FormError cause.

You can customize the keys behaviour by registering a service that implements [ParamResolverInterface](./src/ParamResolver/ParamResolverInterface.php) or [CauseResolverInterface](./src/CauseResolver/CauseResolverInterface.php).

If autoconfiguration is disabled, you have to tag the service with `sbsedv_form.param_resolver` or `sbsedv_form.cause_resolver`.
The normalizer uses tagged iterators, so you can set a "priority" attribute with the tag.

You can also customize the key names and type value with the normalizer context. <br>
See [FormErrorNormalizer](./src/Serializer/Normalizer/FormErrorNormalizer.php).

#### [**UnsubmittedFormNormalizer**](./src/Serializer/Normalizer/UnsubmittedFormNormalizer.php)

This normalizer supports unsubmitted.

The normalized data is an associative array with the following structure:

```json
[
    {
        "message": "The request body does not contain any usable data.",
        "type": "invalid_request_error"
    }
]
```

The error message is customizable via "request_body_is_empty" in the "sbsedv_form" translation domain.

You can also customize the key names and type value with the normalizer context. <br>
See [UnsubmittedFormNormalizer](./src/Serializer/Normalizer/UnsubmittedFormNormalizer.php).

---

## **Form Types**

This bundle registers the following form types:

-   [BooleanType](./src/Form/Type/BooleanType.php)<br>
    Converts boolean values to boolean type

-   [UuidTypeExtension](./src/Form/Extension/UuidTypeExtension.php)<br>
    Extends the default UuidType with optional "as_string" and "nil_to_null" (NilUuid to null) options.

---

## **Data Transformers**

This bundle provides the following data transformers:

-   [CapitalizeStringDataTransformer](./src/Form/DataTransformer/CapitalizeStringDataTransformer.php) (ucfirst)
-   [LowercaseStringDataTransformer](./src/Form/DataTransformer/LowercaseStringDataTransformer.php) (strtolower)
-   [UppercaseStringDataTransformer](./src/Form/DataTransformer/UppercaseStringDataTransformer.php) (strtoupper)
