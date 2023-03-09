<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\AuthToken\AuthKit;
use Tripteki\Helpers\Helpers\ProjectHelper;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use InstallsBackendSanctum, InstallsBackendJWT, InstallsBackendPassport;

    /**
     * @var string
     */
    protected $signature = "auth-api:install {type}";

    /**
     * @var string
     */
    protected $description = "Install the auth stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        $type = $this->argument("type");

        $this->installStack();

        if ($type == AuthKit::SANCTUM_AUTH_KIT) {

            $this->installBackendSanctumStack();

        } else if ($type == AuthKit::JWT_AUTH_KIT) {

            $this->installBackendJWTStack();

        } else if ($type == AuthKit::PASSPORT_AUTH_KIT) {

            $this->installBackendPassportStack();

        } else {

            $this->error("The package requires some official package to handle api tokens.");
            $this->warn("You can choose between Laravel Sanctum (your app would be consumed by webapp & mobileapp) or Laravel TymonJWT (your app would be consumed by mobileapp) or Laravel Passport (your app would be consumed by third party apps with oauth).");
            $this->info("For Laravel sanctum you can run 'composer require laravel/sanctum'.");
            $this->info("For Laravel sanctum you can run 'composer require tymon/jwt-auth'.");
            $this->info("For Laravel passport you can run 'composer require laravel/passport'.");
        }

        return 0;
    }

    /**
     * @return int|null
     */
    protected function installStack()
    {
        $this->call("vendor:publish", [ "--provider" => \Laravel\Fortify\FortifyServiceProvider::class, ]);
    }
};
