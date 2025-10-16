<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('transaction_id')->unique();
            $table->unsignedBigInteger('from_credit_card_id')->nullable();
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('from_pocket_account_id')->nullable();
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->unsignedBigInteger('to_credit_card_id')->nullable();
            $table->unsignedBigInteger('to_beneficiary_account_id')->nullable();
            $table->unsignedBigInteger('to_pocket_account_id')->nullable();
            $table->string('transfer_type')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transfer_channel')->nullable();
            $table->double('pay_amount',8,2)->nullable();
            $table->double('pay_fee',8,2)->nullable();
            $table->double('pay_fee_amount',8,2)->nullable();
            $table->double('total_pay_amount',8,2)->nullable();
            $table->text('notes')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_bill_payment')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('from_pocket_account_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('to_credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('to_beneficiary_account_id')->references('id')->on('beneficiaries')->onDelete('cascade');
            $table->foreign('to_pocket_account_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('account_payments');
    }
}
