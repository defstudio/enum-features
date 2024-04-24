<?php

use DefStudio\EnumFeatures\Tests\Fixtures\Feature;
use Workbench\App\Models\User;

test('defines_feature', function () {
    $enabled = new User([
        'id' => 1,
        'name' => 'enabled_user',
    ]);

    expect(Feature::feature_1->enabled($enabled))->toBeTrue();
});
