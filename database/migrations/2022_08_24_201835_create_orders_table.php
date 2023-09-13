<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderable_id');
            $table->string('orderable_type');
            $table->unsignedBigInteger('address_delivery_id')->nullable()->comment('for shoppers is null');
            $table->string('phone', 13)->nullable();
            $table->bigInteger('total_payment');
            $table->enum('status', Order::ORDER_STATES)->default(Order::ORDER_STATE_PROCESSING);
            $table->boolean('paid')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('address_delivery_id')
                ->references('id')
                ->on('addresses')
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
        Schema::dropIfExists('orders');
    }
};
