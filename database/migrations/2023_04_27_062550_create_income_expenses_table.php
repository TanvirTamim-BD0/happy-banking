<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomeExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('transaction_id')->unique();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('mobile_wallet_id')->nullable();
            $table->unsignedBigInteger('pocket_wallet_id')->nullable();
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('from_credit_card_id')->nullable();
            $table->unsignedBigInteger('transaction_category_id')->nullable();
            $table->string('income_expense_type')->nullable();
            $table->boolean('status')->default(true);
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
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories')->onDelete('cascade');
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
        Schema::dropIfExists('income_expenses');
    }
}
