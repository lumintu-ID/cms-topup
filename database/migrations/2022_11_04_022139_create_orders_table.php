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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('invoice');
            $table->uuid('payment_id');
            $table->uuid('game_id');
            $table->string('user_name')->nullable()->default(null);
            $table->string('user_email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('game_account');
            $table->timestamps();
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
