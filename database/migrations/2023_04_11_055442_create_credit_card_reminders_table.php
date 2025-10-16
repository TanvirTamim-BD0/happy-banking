<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCardRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_card_reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('credit_card_id')->nullable();
            $table->unsignedBigInteger('active_session_id')->nullable();
            $table->string('billing_date')->nullable();
            $table->string('last_payment_date')->nullable();
            $table->double('total_due',8,2)->nullable();
            $table->double('minimum_due',8,2)->nullable();
            $table->double('total_bdt_due',8,2)->nullable();
            $table->double('total_usd_due',8,2)->nullable();
            $table->double('bdt_minimum_due',8,2)->nullable();
            $table->double('usd_minimum_due',8,2)->nullable();
            $table->boolean('status',8,2)->default(false);
            $table->boolean('is_seen')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('active_session_id')->references('id')->on('active_sessions')->onDelete('cascade');
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
        Schema::dropIfExists('credit_card_reminders');
    }
}
