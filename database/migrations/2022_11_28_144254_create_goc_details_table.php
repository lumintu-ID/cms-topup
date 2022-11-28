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
        Schema::create('goc_details', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('merchantId');
            $table->string('payment_method');
            $table->string('trxId');
            $table->string('channelId');
            $table->string('amount');
            $table->string('currency');
            $table->string('paidAmount');
            $table->string('paidCurrency');
            $table->string('referenceId');
            $table->string('sign');
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
        Schema::dropIfExists('goc_details');
    }
};
