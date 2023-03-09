<?php

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * @var string
     */
    protected $keytype;

    /**
     * @return void
     */
    public function __construct()
    {
        $model = app(AuthModelContract::class);

        $this->keytype = $model->getKeyType();
    }

    /**
     * @return void
     */
    public function up()
    {
        $keytype = $this->keytype;

        Schema::create("personal_access_tokens", function (Blueprint $table) use ($keytype) {

            $table->uuid("id");

            if ($keytype == "int") $table->morphs("tokenable");
            else if ($keytype == "string") $table->uuidMorphs("tokenable");

            $table->string("name");
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
        Schema::dropIfExists("personal_access_tokens");
    }
};
