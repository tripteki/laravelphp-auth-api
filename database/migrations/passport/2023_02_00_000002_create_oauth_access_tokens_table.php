<?php

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
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

        Schema::create("oauth_access_tokens", function (Blueprint $table) use ($provider, $keytype, $key) {

            $table->string("id", 100);

            if ($keytype == "int") {

                $table->unsignedBigInteger($key)->index();
                $table->unsignedBigInteger("client_id");

            } else if ($keytype == "string") {

                $table->uuid($key)->index();
                $table->uuid("client_id");
            }

            $table->string("name")->nullable(true);
            $table->text("scopes")->nullable(true);
            $table->boolean("revoked");

            $table->dateTime("expires_at")->nullable(true);
            $table->timestamps();

            $table->primary("id");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("oauth_access_tokens");
    }
};
