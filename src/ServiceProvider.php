<?php

namespace DefStudio\EnumFeatures;

use DefStudio\EnumFeatures\Concerns\DefinesFeatures;
use DefStudio\EnumFeatures\Exceptions\FeatureException;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use UnitEnum;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('enum-features');
    }

    public function packageBooted(): void
    {
        Blade::if('feature', function (UnitEnum $feature) {
            if (! class_uses($feature, DefinesFeatures::class)) {
                throw FeatureException::invalid_feature($feature);
            }

            /** @var DefinesFeatures $feature */
            return $feature->enabled();
        });
    }
}
