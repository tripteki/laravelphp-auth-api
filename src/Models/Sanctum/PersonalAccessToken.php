<?php

namespace Tripteki\AuthToken\Models\Sanctum;

use Tripteki\Uid\Traits\UniqueIdTrait;
use Laravel\Sanctum\PersonalAccessToken as BasePersonalAccessToken;

class PersonalAccessToken extends BasePersonalAccessToken
{
    use UniqueIdTrait;
};
