<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255)->nullable();
//            $table->string('username', 255)->unique()->nullable();
            $table->enum('role', User::ROLES)->default(User::ROLE_CUSTOMER);
            $table->string('phone_number', 14)->unique()->nullable();
            $table->string('card_number', 16)->unique()->nullable();
//            $table->string('avatar', 255)->nullable();
            $table->string('confirm_code', 6)->nullable();
            $table->timestamp('code_expired_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
