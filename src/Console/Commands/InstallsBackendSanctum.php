<?php

namespace Tripteki\AuthApi\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;

trait InstallsBackendSanctum
{
    /**
     * @return int|null
     */
    protected function installBackendSanctumStack()
    {
        if (class_exists("Laravel\Sanctum\Sanctum") && ! class_exists("Laravel\Passport\Passport")) {

            $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Laravel\Sanctum\HasApiTokens::class);

            $this->helper->putMiddleware(null, "ability", \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class);
            $this->helper->putMiddleware(null, "abilities", \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class);
        }
    }
};
