<?php

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthPersonalAccessClientsTable extends Migration
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

        Schema::create("oauth_personal_access_clients", function (Blueprint $table) use ($provider, $keytype, $key) {

            if (config("passport.client_uuids")) $table->uuid("id");
            else $table->bigIncrements("id");

            if ($keytype == "int") {

                $table->unsignedBigInteger("client_id");

            } else if ($keytype == "string") {

                $table->uuid("client_id");
            }

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
        Schema::dropIfExists("oauth_personal_access_clients");
    }
};
