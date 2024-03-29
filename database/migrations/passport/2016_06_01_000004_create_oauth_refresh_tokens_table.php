<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthRefreshTokensTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create("oauth_refresh_tokens", function (Blueprint $table) {

            $table->string("id", 100);
            $table->string("access_token_id", 100)->index();
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
        Schema::dropIfExists("oauth_refresh_tokens");
    }
};
