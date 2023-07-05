# Enum Features

[![Latest Version on Packagist](https://img.shields.io/packagist/v/defstudio/enum-features.svg?style=flat-square)](https://packagist.org/packages/defstudio/enum-features)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/enum-features/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/defstudio/enum-features/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/enum-features/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/defstudio/enum-features/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/defstudio/enum-features.svg?style=flat-square)](https://packagist.org/packages/defstudio/enum-features)
[![License](https://img.shields.io/packagist/l/defstudio/telegraph?style=flat&cacheSeconds=3600)](https://packagist.org/packages/defstudio/enum-features)
[![Twitter Follow](https://img.shields.io/twitter/follow/FabioIvona?label=Follow&style=social)](https://twitter.com/FabioIvona?ref_src=twsrc%5Etfw)

A simple trait to enable a feature system using Enums:

```php
if(AppFeature::welcome_email->enabled()){
    Mail::to($newUser)->send(new WelcomeEmail($newUser));
}
```

## Installation

You can install the package via composer:

```bash
composer require defstudio/enum-features
```

## Usage

Features can be enabled on any enum by using the `DefinesFeatures` trait:

```php
use DefStudio\EnumFeatures\EnumFeatures;

enum AppFeature
{
    use DefinesFeatures; // ← simply add this 

    case multi_language;
    case impersonate;
    case welcome_email;
}
```

and each feature can then added to the Laravel application in its `configs/app.php` file:

```php
// config/app.php

return [
    //..
    
    'features' => [
        AppFeature::multi_language,
        AppFeature::welcome_email,
    ]
]

```

then, in code, a feature could be checked to be enabled:

```php
if(AppFeature::multi_language->enabled()){
    //.. multi language specific code
}
```

or be disabled

```php
if(AppFeature::impersonate->disabled()){
    throw(new Exception("Impersonate feature is not enabled"));
}
```

or enforced

```php

AppFeature::impersonate->enforce(); //throws "Feature [impersonate] is not enabled"

```

### Blade directives

In blade files, a feature can be checked with `@feature` directive:

```html

@feature(AppFeature::multi_language)
<select name="language" xmlns="http://www.w3.org/1999/html">
    <option value="en">English</option>
    <option value="fr">French</option>
    <option value="it">Italian</option>
</select>
@endfeature

```

### Customizing where and how to store enabled features

Enabled features are usually stored in config('app.features'), but this behaviour can be customized by
overriding the `enabledFeatures()` static method inside the enum class:


```php
use DefStudio\EnumFeatures\EnumFeatures;

enum AppFeature
{
    use DefinesFeatures; // ← simply add this 

    case multi_language;
    case impersonate;
    case welcome_email;
    
    public static function enabledFeatures(): array
    {
        return config('my_package.features', []);  //or load from DB, or every other method
    }
}

```
**note:** changing how enabled features are checked makes this package framework agnostic and it can be used in any php applicaiton

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. [Follow Us](https://twitter.com/FabioIvona) on Twitter for more updates about this package.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fabio Ivona](https://github.com/defstudio)
- [def:studio team](https://github.com/defstudio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support us

We at [def:studio](https://github.com/defstudio) strongly believe that open source is the foundation of all our business and we try to contribute to it by helping other projects to grow along with developing and maintaining our packages. You can support our work by sponsoring us on [github](https://github.com/sponsors/defstudio)!
