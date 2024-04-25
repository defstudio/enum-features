<?php

use DefStudio\EnumFeatures\Tests\Fixtures\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function(){
    Model::unguard();
    Feature::defineFeatures();

    $this->enabled_user = new User(['id' => 1, 'name' => 'enabled_user', 'enabled' => true]);

    $this->disabled_user = new User(['id' => 2, 'name' => 'disabled_user', 'enabled' => false]);
});

it('can check if a feature is enabled', function () {


    Auth::setUser($this->enabled_user);
    expect(Feature::enabled_for_some->active())->toBeTrue();

    Auth::setUser($this->disabled_user);
    expect(Feature::enabled_for_some->active())->toBeFalse();
});

it('can check if a feature is enabled for a given user', function () {
    expect(Feature::enabled_for_some->active($this->enabled_user))->toBeTrue();
    expect(Feature::enabled_for_some->active($this->disabled_user))->toBeFalse();
});

it('can check if a feature is disabled', function () {
    Auth::setUser($this->enabled_user);
    expect(Feature::enabled_for_some->inactive())->toBeFalse();

    Auth::setUser($this->disabled_user);
    expect(Feature::enabled_for_some->inactive())->toBeTrue();
});

it('can check if a feature is disabled for a given user', function () {
    expect(Feature::enabled_for_some->inactive($this->enabled_user))->toBeFalse();
    expect(Feature::enabled_for_some->inactive($this->disabled_user))->toBeTrue();
});

it('can define a middleware', function(){
   expect(Feature::enabled_for_some->middleware())->toBe("Laravel\Pennant\Middleware\EnsureFeaturesAreActive:enabled_for_some") ;
});

it('can purge recorded feature values', function () {

    $stored_features = fn() => invade(invade(invade(\Laravel\Pennant\Feature::for($this->enabled_user))->driver)->driver)->resolvedFeatureStates;

    expect($stored_features())->toBeEmpty();

    Feature::enabled_for_some->active($this->enabled_user);
    Feature::enabled_for_all->active($this->enabled_user);

    Feature::enabled_for_some->active($this->disabled_user);
    Feature::enabled_for_all->active($this->disabled_user);


    expect($stored_features())->toBe([
        'enabled_for_some' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => false,
        ],
        'enabled_for_all' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => true,
        ],
    ]);

    Feature::enabled_for_some->purge();

    expect($stored_features())->toBe([
        'enabled_for_all' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => true,
        ],
    ]);
});

it('can forget recorded feature values', function () {

    $stored_features = fn() => invade(invade(invade(\Laravel\Pennant\Feature::for($this->enabled_user))->driver)->driver)->resolvedFeatureStates;

    expect($stored_features())->toBeEmpty();

    Feature::enabled_for_some->active($this->enabled_user);
    Feature::enabled_for_all->active($this->enabled_user);

    Feature::enabled_for_some->active($this->disabled_user);
    Feature::enabled_for_all->active($this->disabled_user);

    expect($stored_features())->toBe([
        'enabled_for_some' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => false,
        ],
        'enabled_for_all' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => true,
        ],
    ]);

    Feature::enabled_for_some->forget($this->enabled_user);

    expect($stored_features())->toBe([
        'enabled_for_some' => [
            'Illuminate\Foundation\Auth\User|2' => false,
        ],
        'enabled_for_all' => [
            'Illuminate\Foundation\Auth\User|1' => true,
            'Illuminate\Foundation\Auth\User|2' => true,
        ],
    ]);
});

it('can enforce a feature', function(){
    Auth::setUser($this->enabled_user);

    expect(fn() => Feature::enabled_for_some->enforce())->not->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);

    Auth::setUser($this->disabled_user);

    expect(fn() => Feature::enabled_for_some->enforce())->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

it('can enforce a feature for a given user', function(){
    expect(fn() => Feature::enabled_for_some->enforce($this->enabled_user))->not->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);

    expect(fn() => Feature::enabled_for_some->enforce($this->disabled_user))->toThrow(\Symfony\Component\HttpKernel\Exception\HttpException::class);
});

it('can activate a feature', function(){
   Auth::setUser($this->disabled_user);

   expect(Feature::enabled_for_some->active())->toBeFalse();

   Feature::enabled_for_some->activate();

    expect(Feature::enabled_for_some->active())->toBeTrue();
});

it('can activate a feature for a given user', function(){
   expect(Feature::enabled_for_some->active($this->disabled_user))->toBeFalse();

   Feature::enabled_for_some->activate($this->disabled_user);

    expect(Feature::enabled_for_some->active($this->disabled_user))->toBeTrue();
});

it('can deactivate a feature', function(){
   Auth::setUser($this->enabled_user);

   expect(Feature::enabled_for_some->active())->toBeTrue();

   Feature::enabled_for_some->deactivate();

    expect(Feature::enabled_for_some->active())->toBeFalse();
});

it('can deactivate a feature for a given user', function(){
   expect(Feature::enabled_for_some->active($this->enabled_user))->toBeTrue();

   Feature::enabled_for_some->deactivate($this->enabled_user);

    expect(Feature::enabled_for_some->active($this->enabled_user))->toBeFalse();
});

it('can check if all features are active', function(){
    Auth::setUser($this->enabled_user);

   expect(Feature::areAllActive([
        Feature::enabled_for_some,
        Feature::enabled_for_all,
   ]))->toBeTrue();

    Auth::setUser($this->disabled_user);

    expect(Feature::areAllActive([
        Feature::enabled_for_some,
        Feature::enabled_for_all,
    ]))->toBeFalse();
});

it('can check if some features are active', function () {
    Auth::setUser($this->enabled_user);

    expect(Feature::someAreActive([
        Feature::enabled_for_some,
        Feature::enabled_for_all,
    ]))->toBeTrue();

    expect(Feature::someAreActive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeTrue();

    Auth::setUser($this->disabled_user);

    expect(Feature::someAreActive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeFalse();
});

it('can check if all features are inactive', function () {
    Auth::setUser($this->enabled_user);

    expect(Feature::areAllInactive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeFalse();

    Auth::setUser($this->disabled_user);

    expect(Feature::areAllInactive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeTrue();
});

it('can check if some features are inactive', function () {
    Auth::setUser($this->enabled_user);

    expect(Feature::someAreInactive([
        Feature::enabled_for_some,
        Feature::enabled_for_all,
    ]))->toBeFalse();

    expect(Feature::someAreInactive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeTrue();

    Auth::setUser($this->disabled_user);

    expect(Feature::someAreInactive([
        Feature::enabled_for_some,
        Feature::enabled_for_none,
    ]))->toBeTrue();
});
