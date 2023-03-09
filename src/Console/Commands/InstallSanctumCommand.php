<?php

namespace Tripteki\AuthToken\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Console\Command;

class InstallSanctumCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "sanctum:install";

    /**
     * @return int
     */
    public function handle()
    {
        $model = app(AuthModelContract::class);

        $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-migrations-sanctum", ]);

        if ($this->confirm("Would you run the migration?")) {

            $this->call("migrate");
            $this->line("");
        }

        if ($model->getKeyType() == "int") {

            $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-sanctum", ]);

        } else if ($model->getKeyType() == "string") {

            $this->call("vendor:publish", [ "--tag" => "tripteki-laravelphp-auth-api-sanctum-uuid", ]);
            config([ "sanctum.uuids" => true, ]);
        }

        if ($this->confirm("Would you re-run the migration?")) {

            $this->call("migrate:rollback");
            $this->call("migrate");
            $this->line("");
        }
    }
};
