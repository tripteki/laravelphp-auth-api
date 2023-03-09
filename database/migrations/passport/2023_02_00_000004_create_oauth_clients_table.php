<?php

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $keytype;

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
        $this->keytype = $model->getKeyType();
        $this->key = foreignKeyName($provider);
    }

    /**
     * @return void
     */
    public function up()
    {
        $provider = $this->provider;
        $keytype = $this->keytype;
        $key = $this->key;

        Schema::create("oauth_clients", function (Blueprint $table) use ($provider, $keytype, $key) {

            if (config("passport.client_uuids")) $table->uuid("id");
            else $table->bigIncrements("id");

            if ($keytype == "int") {

                $table->unsignedBigInteger($key)->nullable(true)->index();

            } else if ($keytype == "string") {

                $table->uuid($key)->nullable(true)->index();
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
        Schema::dropIfExists("oauth_clients");
    }
};
