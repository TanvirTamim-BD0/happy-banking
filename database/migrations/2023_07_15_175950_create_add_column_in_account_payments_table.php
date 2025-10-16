<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddColumnInAccountPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_payments', function (Blueprint $table) {
            $table->string('transfer_currency_type', 255)->nullable()->after('transfer_channel');
            $table->double('usd_in_bdt_rate', 8,2)->nullable()->after('transfer_currency_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_payments', function($table) {
            $table->dropColumn('transfer_currency_type');
            $table->dropColumn('usd_in_bdt_rate');
        });
    }
}
