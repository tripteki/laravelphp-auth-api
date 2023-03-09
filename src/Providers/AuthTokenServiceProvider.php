<?php

namespace Tripteki\AuthToken\Providers;

use Tripteki\AuthToken\AuthKit;
use Tripteki\AuthToken\Models\Sanctum\PersonalAccessToken;
use Tripteki\AuthToken\Models\Passport\Token;
use Tripteki\AuthToken\Models\Passport\RefreshToken;
use Tripteki\AuthToken\Models\Passport\AuthCode;
use Tripteki\AuthToken\Models\Passport\Client;
use Tripteki\AuthToken\Models\Passport\PersonalAccessClient;
use Tripteki\AuthToken\Console\Commands\InstallCommand;
use Tripteki\AuthToken\Console\Commands\InstallSanctumCommand;
use Tripteki\AuthToken\Console\Commands\InstallPassportCommand;
use Tripteki\Uid\Observers\UniqueIdObserver;
use Illuminate\Support\ServiceProvider;

class AuthTokenServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * @return bool
     */
    public static function shouldRunMigrations()
    {
        return static::$runsMigrations;
    }

    /**
     * @return void
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;
    }

    /**
     * @return void
     */
    public function register()
    {
        if (AuthKit::isSanctum()) \Laravel\Sanctum\Sanctum::ignoreMigrations();
        else if (AuthKit::isPassport()) \Laravel\Passport\Passport::ignoreMigrations();
        static::ignoreMigrations();
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->dataEventListener();

        $this->registerPublishers();
        $this->registerCommands();
        $this->registerMigrations();
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->isProduction() && $this->app->runningInConsole()) {

            $command = [];

            if (AuthKit::isSanctum()) $command = [ InstallSanctumCommand::class, ];
            else if (AuthKit::isPassport()) $command = [ InstallPassportCommand::class, ];

            $this->commands(
                array_merge([
                    InstallCommand::class,
                ], $command)
            );
        }
    }

    /**
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && static::shouldRunMigrations()) {

            if (AuthKit::isSanctum()) {

                $this->loadMigrationsFrom(__DIR__."/../../database/migrations/sanctum");

            } else if (AuthKit::isPassport()) {

                $this->loadMigrationsFrom(__DIR__."/../../database/migrations/passport");
            }
        }
    }

    /**
     * @return void
     */
    protected function registerPublishers()
    {
        $this->publishes(
        [
            __DIR__."/../../config/sanctum.php" => config_path("sanctum.php"),
        ],

        "tripteki-laravelphp-auth-api-sanctum");

        $this->publishes(
        [
            __DIR__."/../../config/sanctum-uuid.php" => config_path("sanctum.php"),
        ],

        "tripteki-laravelphp-auth-api-sanctum-uuid");

        $this->publishes(
        [
            __DIR__."/../../config/passport.php" => config_path("passport.php"),
        ],

        "tripteki-laravelphp-auth-api-passport");

        $this->publishes(
        [
            __DIR__."/../../config/passport-uuid.php" => config_path("passport.php"),
        ],

        "tripteki-laravelphp-auth-api-passport-uuid");

        if (! static::shouldRunMigrations()) {

            $this->publishes(
            [
                __DIR__."/../../database/migrations/sanctum" => database_path("migrations"),
            ],

            "tripteki-laravelphp-auth-api-migrations-sanctum");

            $this->publishes(
            [
                __DIR__."/../../database/migrations/passport" => database_path("migrations"),
            ],

            "tripteki-laravelphp-auth-api-migrations-passport");
        }
    }

    /**
     * @return void
     */
    public function dataEventListener()
    {
        if (AuthKit::isSanctum()) {

            \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

            PersonalAccessToken::observe(UniqueIdObserver::class);

        } else if (AuthKit::isPassport()) {

            \Laravel\Passport\Passport::useTokenModel(Token::class);
            \Laravel\Passport\Passport::useRefreshTokenModel(RefreshToken::class);
            \Laravel\Passport\Passport::useAuthCodeModel(AuthCode::class);
            \Laravel\Passport\Passport::useClientModel(Client::class);
            \Laravel\Passport\Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

            if (config("passport.client_uuids")) {

                Client::observe(UniqueIdObserver::class);
                PersonalAccessClient::observe(UniqueIdObserver::class);
            }
        }
    }
};
