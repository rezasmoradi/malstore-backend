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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_group_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('discount_value');
            $table->enum('discount_unit', \App\Models\Discount::DISCOUNT_UNITS);
            $table->integer('max_number_uses')->nullable()->comment('not per user');
            $table->integer('min_order_quantity')->default(1);
            $table->bigInteger('max_discount_amount')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('expired_at')->nullable();
            $table->boolean('active');
            $table->string('coupon_code', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_group_id')
                ->on('customer_groups')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('product_id')
                ->on('products')
                ->references('id')
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
        Schema::dropIfExists('discounts');
    }
};
