<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name', 255)->unique();
            $table->string('display_name', 255);
            $table->string('model', 255);
            $table->string('slug', 255)->unique();
            $table->integer('width')->comment('in cm');
            $table->integer('length');
            $table->integer('height')->nullable();
            $table->text('long_desc');
            $table->text('short_desc')->nullable();
            $table->unsignedInteger('weight')->comment('in gram');
            $table->bigInteger('unit_price');
            $table->boolean('active')->default(true);
            $table->text('best_features')->nullable();
            $table->string('meta_description', 255);
            $table->string('meta_keywords', 255);
            $table->string('meta_title', 128);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('cascade')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
