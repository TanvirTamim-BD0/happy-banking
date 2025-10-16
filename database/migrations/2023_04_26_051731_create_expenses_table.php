<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('mobile_wallet_id')->nullable();
            $table->unsignedBigInteger('pocket_wallet_id')->nullable();
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('from_credit_card_id')->nullable();
            $table->unsignedBigInteger('source_of_expense_id')->nullable();
            $table->string('expense_type')->nullable();
            $table->double('amount',8,2)->nullable();
            $table->text('notes')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('mobile_wallet_id')->references('id')->on('mobile_wallets')->onDelete('cascade');
            $table->foreign('pocket_wallet_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('from_credit_card_id')->references('id')->on('credit_cards')->onDelete('cascade');
            $table->foreign('source_of_expense_id')->references('id')->on('transaction_categories')->onDelete('cascade');
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
        Schema::dropIfExists('expenses');
    }
}
