<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tripteki\Helpers\Contracts\AuthModelContract;

class CreateOauthAuthCodesTable extends Migration
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

        Schema::create("oauth_auth_codes", function (Blueprint $table) use ($provider, $key) {

            $table->string("id", 100);

            if (! config("passport.client_uuids")) {

                $table->foreignId($key)->constrained($provider)->onUpdate("cascade")->onDelete("cascade");
                $table->foreignId("client_id")->constrained("oauth_clients")->onUpdate("cascade")->onDelete("cascade");

            } else {

                $table->foreignUuid($key)->references("id")->on($provider)->onUpdate("cascade")->onDelete("cascade");
                $table->foreignUuid("client_id")->references("id")->on("oauth_clients")->onUpdate("cascade")->onDelete("cascade");
            }

            $table->text("scopes")->nullable(true);
            $table->boolean("revoked");

            $table->dateTime("expires_at")->nullable(true);

            $table->primary("id");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists("oauth_auth_codes");
    }
};
