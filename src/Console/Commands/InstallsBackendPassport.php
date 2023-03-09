<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\AuthToken\AuthKit;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Filesystem\Filesystem;

trait InstallsBackendPassport
{
    /**
     * @return int|null
     */
    protected function installBackendPassportStack()
    {
        if (AuthKit::isPassport()) {

            $model = app(AuthModelContract::class);
            $keytype = $model->getKeyType();

            if ($keytype == "int") $this->call("passport:install", [ "--force" => true, ]);
            else if ($keytype == "string") $this->call("passport:install", [ "--uuids" => true, "--force" => true, ]);
            $this->call("passport:keys", [ "--force" => true, ]);
            $this->warn("Do not forget to run `php artisan passport:client --personal`!");

            $this->helper->putTrait($this->helper->classToFile(get_class($model)), \Laravel\Passport\HasApiTokens::class);

            $this->helper->putMiddleware(null, "client", \Laravel\Passport\Http\Middleware\CheckClientCredentials::class);
            $this->helper->putMiddleware(null, "scope", \Laravel\Passport\Http\Middleware\CheckForAnyScope::class);
            $this->helper->putMiddleware(null, "scopes", \Laravel\Passport\Http\Middleware\CheckScopes::class);

            $this->info("Passport scaffolding installed successfully.");
        }
    }
};
