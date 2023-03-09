<?php

namespace Tripteki\AuthApi\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;

trait InstallsBackendPassport
{
    /**
     * @return int|null
     */
    protected function installBackendPassportStack()
    {
        if (class_exists("Laravel\Passport\Passport") && ! class_exists("Laravel\Sanctum\Sanctum")) {

            $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Laravel\Passport\HasApiTokens::class);

            $this->helper->putMiddleware(null, "client", \Laravel\Passport\Http\Middleware\CheckClientCredentials::class);
            $this->helper->putMiddleware(null, "scope", \Laravel\Passport\Http\Middleware\CheckForAnyScope::class);
            $this->helper->putMiddleware(null, "scopes", \Laravel\Passport\Http\Middleware\CheckScopes::class);
        }
    }
};
