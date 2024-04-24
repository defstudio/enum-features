<?php

use DefStudio\EnumFeatures\Tests\Fixtures\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

test('can check if a feature is enabled', function () {
    Model::unguard();
    Feature::defineFeatures();

    $enabled_user = new User(['enabled' => true]);

    $disabled_user = new User(['enabled' => false]);

    expect(Feature::enabled_for_some->enabled($enabled_user))->toBeTrue();
    expect(Feature::enabled_for_some->active($enabled_user))->toBeTrue();
    expect(Feature::enabled_for_some->disabled($disabled_user))->toBeTrue();
    expect(Feature::enabled_for_some->inactive($disabled_user))->toBeTrue();
});
