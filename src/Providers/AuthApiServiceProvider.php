<?php

namespace Tripteki\AuthApi\Providers;

use Tripteki\Uid\Observers\UniqueIdObserver;
use Tripteki\AuthApi\Models\Sanctum\PersonalAccessToken;
use Tripteki\AuthApi\Models\Passport\Token;
use Tripteki\AuthApi\Models\Passport\RefreshToken;
use Tripteki\AuthApi\Models\Passport\AuthCode;
use Tripteki\AuthApi\Models\Passport\Client;
use Tripteki\AuthApi\Models\Passport\PersonalAccessClient;
use Tripteki\AuthApi\Console\Commands\InstallCommand;
use Tripteki\Repository\Providers\RepositoryServiceProvider as ServiceProvider;

class AuthApiServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $repositories =
    [
        \Tripteki\AuthApi\Contracts\Repository\Admin\IUserRepository::class => \Tripteki\AuthApi\Repositories\Eloquent\Admin\UserRepository::class,
    ];

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
        if (class_exists("Laravel\Sanctum\Sanctum")) \Laravel\Sanctum\Sanctum::ignoreMigrations();
        else if (class_exists("Laravel\Passport\Passport")) \Laravel\Passport\Passport::ignoreMigrations();
    }

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->dataEventListener();

        $this->registerPublishers();
        $this->registerConfigs();
        $this->registerCommands();
        $this->registerMigrations();
    }

    /**
     * @return void
     */
    protected function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__."/../../config/auth-api.php", "auth-api");
    }

    /**
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && static::shouldRunMigrations()) {

            if (class_exists("Laravel\Sanctum\Sanctum")) {

                $this->loadMigrationsFrom(__DIR__."/../../database/migrations/sanctum");

            } else if (class_exists("Laravel\Passport\Passport")) {

                $this->loadMigrationsFrom(__DIR__."/../../database/migrations/passport");
            }
        }
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->isProduction() && $this->app->runningInConsole()) {

            $this->commands(
            [
                InstallCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function registerPublishers()
    {
        $this->publishes(
        [
            __DIR__."/../../config/auth-api.php" => config_path("auth-api.php"),
        ],

        "tripteki-laravelphp-auth-api-configs");

        if (! static::shouldRunMigrations()) {

            $this->publishes(
            [
                __DIR__."/../../database/migrations/sanctum" => database_path("migrations"),
                __DIR__."/../../database/migrations/passport" => database_path("migrations"),
            ],

            "tripteki-laravelphp-auth-api-migrations");
        }
    }

    /**
     * @return void
     */
    public function dataEventListener()
    {
        if (class_exists("Laravel\Sanctum\Sanctum")) {

            PersonalAccessToken::observe(UniqueIdObserver::class);

            \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        } else if (class_exists("Laravel\Passport\Passport")) {

            if (config("passport.client_uuids")) Client::observe(UniqueIdObserver::class);
            if (config("passport.client_uuids")) PersonalAccessClient::observe(UniqueIdObserver::class);

            \Laravel\Passport\Passport::useTokenModel(Token::class);
            \Laravel\Passport\Passport::useRefreshTokenModel(RefreshToken::class);
            \Laravel\Passport\Passport::useAuthCodeModel(AuthCode::class);
            \Laravel\Passport\Passport::useClientModel(Client::class);
            \Laravel\Passport\Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        }
    }
};
