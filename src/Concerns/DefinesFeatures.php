<?php

/** @noinspection PhpUnused */

/** @noinspection PhpUnhandledExceptionInspection */

namespace DefStudio\EnumFeatures\Concerns;

use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Pennant\Feature as Pennant;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

trait DefinesFeatures
{
    public function define(): void
    {
        Pennant::define($this->featureName(), $this->resolve(...));
    }

    protected function featureName(): string
    {
        return $this instanceof BackedEnum ? $this->value : $this->name;
    }

    public function active(?Authenticatable $scope = null): bool
    {
        if ($scope) {
            return Pennant::for($scope)->active($this->featureName());
        }

        return Pennant::active($this->featureName());
    }

    public function inactive(?Authenticatable $scope = null): bool
    {
        return ! $this->active($scope);
    }

    public function middleware(): string
    {
        return EnsureFeaturesAreActive::using($this->featureName());
    }

    protected function resolve(?Authenticatable $scope = null): bool
    {
        $featureName = $this->featureName();
        $camelFeatureName = str($this->featureName())->camel()->ucfirst();

        $try_methods = [
            "resolve_$featureName",
            "resolve_{$featureName}_feature",
            "resolve$camelFeatureName",
            "resolve{$camelFeatureName}Feature",
        ];

        foreach ($try_methods as $method) {
            if (method_exists($this, $method)) {
                return $this->{$method}($scope);
            }
        }

        return false;
    }

    public function enforce(?Authenticatable $scope = null): void
    {
        if (! $this->active($scope)) {
            abort(403);
        }
    }

    public function activate(?Authenticatable $scope = null): void
    {
        if ($scope) {
            Pennant::for($scope)->activate($this->featureName());

            return;
        }

        Pennant::activate($this->featureName());
    }

    public function deactivate(?Authenticatable $scope = null): void
    {
        if ($scope) {
            Pennant::for($scope)->deactivate($this->featureName());

            return;
        }

        Pennant::deactivate($this->featureName());
    }

    public function forget(?Authenticatable $scope = null): void
    {
        if ($scope) {
            Pennant::for($scope)->forget($this->featureName());

            return;
        }

        Pennant::forget($this->featureName());
    }

    public function purge(): void
    {
        Pennant::purge($this->featureName());
    }

    public static function defineFeatures(): void
    {
        collect(self::cases())->each->define();
    }

    /**
     * @param  array<self>  $features
     */
    public static function areAllActive(array $features): bool
    {
        return Pennant::allAreActive(collect($features)
            ->map(fn (self $feature) => $feature->featureName())
            ->toArray());
    }

    /**
     * @param  array<self>  $features
     */
    public static function someAreActive(array $features): bool
    {
        return Pennant::someAreActive(collect($features)
            ->map(fn (self $feature) => $feature->featureName())
            ->toArray());
    }

    /**
     * @param  array<self>  $features
     */
    public static function areAllInactive(array $features): bool
    {
        return Pennant::allAreInactive(collect($features)
            ->map(fn (self $feature) => $feature->featureName())
            ->toArray());
    }

    /**
     * @param  array<self>  $features
     */
    public static function someAreInactive(array $features): bool
    {
        return Pennant::someAreInactive(collect($features)
            ->map(fn (self $feature) => $feature->featureName())
            ->toArray());
    }
}
