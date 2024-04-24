<?php

namespace DefStudio\EnumFeatures\Tests\Fixtures;

use DefStudio\EnumFeatures\Concerns\DefinesFeatures;
use Illuminate\Contracts\Auth\Authenticatable;

enum Feature
{
    use DefinesFeatures;

    case enabled_for_some;
    case enabled_for_none;
    case enabled_for_all;

    protected function resolve(?Authenticatable $scope = null): bool
    {
        return match ($this) {
            self::enabled_for_all => true,
            self::enabled_for_none => false,
            default => $scope->enabled,
        };
    }
}
