<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('photo', 255);
            $table->string('url', 255);
            $table->enum('type', \App\Models\Slide::URL_TYPES);
            $table->string('first_feature', 255);
            $table->string('second_feature', 255);
            $table->string('third_feature', 255)->nullable();
            $table->timestamp('published_at');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slides');
    }
};
