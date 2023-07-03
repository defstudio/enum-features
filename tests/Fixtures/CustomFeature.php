<?php

namespace DefStudio\EnumFeatures\Tests\Fixtures;

use DefStudio\EnumFeatures\Concerns\DefinesFeatures;

enum CustomFeature
{
    use DefinesFeatures;

    case guest_account;
    case payments;

    public static function enabledFeatures(): array
    {
        return config('my_package.enabled_features', []);
    }
}
