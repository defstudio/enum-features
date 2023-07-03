<?php

use DefStudio\EnumFeatures\Exceptions\FeatureException;
use DefStudio\EnumFeatures\Tests\Fixtures\CustomFeature;

it('can check if a feature is enabled', function () {
    expect(CustomFeature::guest_account->enabled())->toBeTrue();

    expect(CustomFeature::payments->enabled())->toBeFalse();
});

it('can check if a feature is not enabled', function () {
    expect(CustomFeature::guest_account->disabled())->toBeFalse();

    expect(CustomFeature::payments->disabled())->toBeTrue();
});

it('can enforce a feature to be enabled', function () {
    CustomFeature::guest_account->enforce();

    expect(fn () => CustomFeature::payments->enforce())
        ->toThrow(FeatureException::class, 'Feature [payments] is not enabled');
});
