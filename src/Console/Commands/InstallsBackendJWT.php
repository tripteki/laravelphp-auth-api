<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\AuthToken\AuthKit;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Filesystem\Filesystem;

trait InstallsBackendJWT
{
    /**
     * @return int|null
     */
    protected function installBackendJWTStack()
    {
        if (AuthKit::isJWT()) {

            $this->call("vendor:publish", [ "--provider" => \Tymon\JWTAuth\Providers\LaravelServiceProvider::class, ]);
            $this->call("jwt:secret");

            $this->info("JWT scaffolding installed successfully.");
        }
    }
};
