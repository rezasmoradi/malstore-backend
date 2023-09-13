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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addressable_id');
            $table->string('addressable_type', 45);
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('address', 255)->nullable()->comment('null for supplier');
            $table->string('postal_code', 10)->nullable()->comment('null for supplier');
            $table->integer('plaque')->default(0);
            $table->unique(['province', 'city', 'address', 'postal_code']);
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
        Schema::dropIfExists('addresses');
    }
};
