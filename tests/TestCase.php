<?php

namespace DefStudio\EnumFeatures\Tests;

use DefStudio\EnumFeatures\Tests\Fixtures\Feature;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('app.features', [
            Feature::multi_language,
            Feature::welcome_email,
        ]);
    }
}
