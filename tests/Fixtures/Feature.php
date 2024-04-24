<?php

namespace DefStudio\EnumFeatures\Tests\Fixtures;

use DefStudio\EnumFeatures\Concerns\DefinesFeatures;
use Illuminate\Contracts\Auth\Authenticatable;

enum Feature
{
    use DefinesFeatures;

    case feature_1;
    case feature_2;
    case feature_3;

    protected function resolve(?Authenticatable $scope = null): bool
    {
        dump($scope);

        return $scope->name === 'enabled_user';
    }
}
