<?php

use DefStudio\EnumFeatures\Exceptions\FeatureException;
use DefStudio\EnumFeatures\Tests\Fixtures\Feature;

it('can check if a feature is enabled', function () {
    expect(Feature::welcome_email->enabled())->toBeTrue();

    expect(Feature::other_feature->enabled())->toBeFalse();
});

it('can check if a feature is not enabled', function () {
    expect(Feature::welcome_email->disabled())->toBeFalse();

    expect(Feature::other_feature->disabled())->toBeTrue();
});

it('can enforce a feature to be enabled', function () {
    Feature::welcome_email->enforce();

    expect(fn () => Feature::other_feature->enforce())
        ->toThrow(FeatureException::class, 'Feature [other_feature] is not enabled');
});
