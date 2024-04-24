<?php

namespace DefStudio\EnumFeatures\Tests;

use Laravel\Pennant\PennantServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PennantServiceProvider::class,
        ];
    }
}
