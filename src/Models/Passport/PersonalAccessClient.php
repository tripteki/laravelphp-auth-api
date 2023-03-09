<?php

namespace Tripteki\AuthToken\Models\Passport;

use Laravel\Passport\PersonalAccessClient as BasePersonalAccessClient;

class PersonalAccessClient extends BasePersonalAccessClient
{
    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return ! config("passport.client_uuids");
    }

    /**
     * @return string
     */
    public function getKeyType()
    {
        return config("passport.client_uuids") ? "string" : "int";
    }
};
