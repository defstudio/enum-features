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

    public function enabled(?Authenticatable $scope = null): bool
    {
        return $this->active($scope);
    }

    public function inactive(?Authenticatable $scope = null): bool
    {
        return ! $this->active($scope);
    }

    public function disabled(?Authenticatable $scope = null): bool
    {
        return $this->inactive($scope);
    }

    public function middleware(): string
    {
        return EnsureFeaturesAreActive::using($this->featureName());
    }

    public function purge(): void
    {
        Pennant::purge($this->featureName());
    }

    protected function resolve(Authenticatable $scope = null): bool
    {
        $featureName = $this->featureName();
        $camelFeatureName = str($this->featureName())->camel()->ucfirst();

        $try_methods = [
            "resolve_$featureName",
            "resolve_{$featureName}_feature",
            "check_$featureName",
            "check_{$featureName}_feature",
            "has_$featureName",
            "has_{$featureName}Feature",
            "resolve$camelFeatureName",
            "resolve{$camelFeatureName}Feature",
            "check$camelFeatureName",
            "check{$camelFeatureName}Feature",
            "has$camelFeatureName",
            "has{$camelFeatureName}Feature",
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
            abort(400);
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

    public function enable(?Authenticatable $scope = null): void
    {
        $this->activate($scope);
    }

    public function deactivate(?Authenticatable $scope = null): void
    {
        if ($scope) {
            Pennant::for($scope)->deactivate($this->featureName());

            return;
        }

        Pennant::deactivate($this->featureName());
    }

    public function disable(?Authenticatable $scope = null): void
    {
        $this->deactivate($scope);
    }

    public function forget(?Authenticatable $scope = null): void
    {
        if ($scope) {
            Pennant::for($scope)->forget($this->featureName());

            return;
        }

        Pennant::forget($this->featureName());
    }

    /**
     * @param  array<self>  $features
     */
    public static function areAllActive(array $features): bool
    {
        return Pennant::allAreInactive(collect($features)
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
    public static function areAllEnabled(array $features): bool
    {
        return self::areAllActive($features);
    }

    /**
     * @param  array<self>  $features
     */
    public static function someAreEnabled(array $features): bool
    {
        return self::someAreActive($features);
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

    /**
     * @param  array<self>  $features
     */
    public static function areAllDisabled(array $features): bool
    {
        return self::areAllInactive($features);
    }

    /**
     * @param  array<self>  $features
     */
    public static function someAreDisabled(array $features): bool
    {
        return self::someAreInactive($features);
    }
}
