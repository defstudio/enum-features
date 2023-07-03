<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\EnumFeatures\Concerns;

use DefStudio\EnumFeatures\Exceptions\FeatureException;

trait DefinesFeatures
{
    public static function enabledFeatures(): array
    {
        return config('app.features', []);
    }

    public function enabled(): bool
    {
        return in_array($this, self::enabledFeatures());
    }

    public function enforce(): void
    {
        /** @noinspection PhpParamsInspection */
        throw_if(! $this->enabled(), FeatureException::notEnabled($this));
    }

    public function disabled(): bool
    {
        return ! $this->enabled();
    }
}
