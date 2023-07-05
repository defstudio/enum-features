<?php

namespace DefStudio\EnumFeatures\Exceptions;

use BackedEnum;
use Exception;
use UnitEnum;

class FeatureException extends Exception
{
    public static function notEnabled(UnitEnum $feature): FeatureException
    {
        $name = $feature instanceof BackedEnum ? $feature->value : $feature->name;

        return new self("Feature [$name] is not enabled");
    }

    public static function invalid_feature_enum(UnitEnum $feature): FeatureException
    {
        $name = $feature instanceof BackedEnum ? $feature->value : $feature->name;

        return new self("Enum [$name] does not use DefineFeatures trait");
    }
}
