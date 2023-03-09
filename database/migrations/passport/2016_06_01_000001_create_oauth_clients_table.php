<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tripteki\Helpers\Contracts\AuthModelContract;

class CreateOauthClientsTable extends Migration
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

        Schema::create("oauth_clients", function (Blueprint $table) use ($provider, $key) {

            if (! config("passport.client_uuids")) $table->bigIncrements("id");
            else $table->uuid("id");

            if (! config("passport.client_uuids")) {

                $table->foreignId($key)->nullable(true)->constrained($provider)->onUpdate("cascade")->onDelete("cascade");

            } else {

                $table->foreignUuid($key)->nullable(true)->references("id")->on($provider)->onUpdate("cascade")->onDelete("cascade");
            }

            $table->string("name");
            $table->string("secret", 100)->nullable(true);
            $table->string("provider")->nullable(true);
            $table->text("redirect");
            $table->boolean("personal_access_client");
            $table->boolean("password_client");
            $table->boolean("revoked");

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
        Schema::dropIfExists("oauth_clients");
    }
};
