<?php

use DefStudio\EnumFeatures\Tests\Fixtures\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

test('defines_feature', function () {
    Model::unguard();

    $enabled = new User([
        'id' => 1,
        'name' => 'enabled_user',
    ]);

    expect(Feature::feature_1->enabled($enabled))->toBeTrue();
});
