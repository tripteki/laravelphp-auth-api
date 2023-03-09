<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create("personal_access_tokens", function (Blueprint $table) {

            $table->uuid("id");

            if (! config("sanctum.uuids")) $table->morphs("tokenable");
            else $table->uuidMorphs("tokenable");

            $table->string("name", 512);
            $table->string("token", 64);
            $table->text("abilities")->nullable(true);

            $table->timestamp("last_used_at")->nullable(true);
            $table->timestamp("expires_at")->nullable(true);
            $table->timestamps();

            $table->primary("id");
            $table->unique("token");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists("personal_access_tokens");
    }
};
