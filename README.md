# Enum Features

[![Latest Version on Packagist](https://img.shields.io/packagist/v/defstudio/enum-features.svg?style=flat-square)](https://packagist.org/packages/defstudio/enum-features)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/enum-features/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/defstudio/enum-features/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/enum-features/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/defstudio/enum-features/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/defstudio/enum-features.svg?style=flat-square)](https://packagist.org/packages/defstudio/enum-features)
[![License](https://img.shields.io/packagist/l/defstudio/telegraph?style=flat&cacheSeconds=3600)](https://packagist.org/packages/defstudio/enum-features)
[![Twitter Follow](https://img.shields.io/twitter/follow/FabioIvona?label=Follow&style=social)](https://twitter.com/FabioIvona?ref_src=twsrc%5Etfw)

A simple trait to enable a feature system using Enums:

```php
if(AppFeature::welcome_email->active()){
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
    
    /* Feature resolution */
    
    //with a single method:
    protected function resolve(Authenticatable $user = null) {
        match($this){
            case self::multi_language => true,
            case self::impersonate => $user->isAdmin(),
            default => false;
        }
    }
    
    //or with a dedicated method:
    
    protected function resolveImpersonate(Authenticatable $user = null){
        return $user->isSuperAdmin();
    }
}
```

and should be registered in your Provider

```php
class AppServiceProvider extends ServiceProvider
{
    //..
    
    public function boot(): void {
        AppFeature::defineFeatures();
    }
}
```


then, in code, a feature could be checked to be enabled:

```php
if(AppFeature::multi_language->active()){
    //.. multi language specific code
}
```

An extensive documentation is available at

https://docs.defstudio.it/enum-features

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
