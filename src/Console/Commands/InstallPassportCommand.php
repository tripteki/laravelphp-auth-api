<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Laravel\Passport\Passport;
use Laravel\Passport\Console\InstallCommand as Command;

class InstallPassportCommand extends Command
{
    /**
     * @return int
     */
    public function handle()
    {
        $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-migrations-passport", ]);

        if ($this->confirm("Would you run the migration?")) {

            $this->call("migrate");
            $this->line("");
        }

        parent::handle();
    }

    /**
     * @return void
     */
    protected function configureUuids()
    {
        $model = app(AuthModelContract::class);

        if ($model->getKeyType() == "int") {

            $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-passport", ]);

        } else if ($model->getKeyType() == "string") {

            $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-passport-uuid", ]);
            config([ "passport.client_uuids" => true, ]);
            Passport::setClientUuids(true);
        }

        if ($this->confirm("Would you re-run the migration?")) {

            $this->call("migrate:rollback");
            $this->call("migrate");
            $this->line("");
        }
    }
};
