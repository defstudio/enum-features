<?php

namespace DefStudio\EnumFeatures\Tests\Fixtures;

use DefStudio\EnumFeatures\Concerns\DefinesFeatures;

enum Feature
{
    use DefinesFeatures;

    case multi_language;
    case welcome_email;
    case other_feature;
}
