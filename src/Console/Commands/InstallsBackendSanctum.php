<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\AuthToken\AuthKit;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Filesystem\Filesystem;

trait InstallsBackendSanctum
{
    /**
     * @return int|null
     */
    protected function installBackendSanctumStack()
    {
        if (AuthKit::isSanctum()) {

            $this->call("sanctum:install");

            $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Laravel\Sanctum\HasApiTokens::class);

            $this->helper->putMiddleware(null, "ability", \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class);
            $this->helper->putMiddleware(null, "abilities", \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class);

            $this->info("Sanctum scaffolding installed successfully.");
        }
    }
};
