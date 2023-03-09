<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tripteki\Helpers\Contracts\AuthModelContract;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $key;

    /**
     * @return void
     */
    public function __construct()
    {
        $model = app(AuthModelContract::class);

        $this->provider = $model->getTable();
        $this->key = foreignKeyName($model);
    }

    /**
     * @return void
     */
    public function up()
    {
        $provider = $this->provider;
        $key = $this->key;

        Schema::create("oauth_personal_access_clients", function (Blueprint $table) use ($provider, $key) {

            if (! config("passport.client_uuids")) $table->bigIncrements("id");
            else $table->uuid("id");

            if (! config("passport.client_uuids")) {

                $table->foreignId("client_id")->nullable(true)->constrained("oauth_clients")->onUpdate("cascade")->onDelete("cascade");

            } else {

                $table->foreignUuid("client_id")->nullable(true)->references("id")->on("oauth_clients")->onUpdate("cascade")->onDelete("cascade");
            }

            $table->boolean("revoked")->default(false);

            $table->timestamps();

            if (config("passport.client_uuids")) $table->primary("id");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists("oauth_personal_access_clients");
    }
};
