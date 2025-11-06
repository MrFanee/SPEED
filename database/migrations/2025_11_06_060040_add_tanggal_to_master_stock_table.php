<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalToMasterStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_stock', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('master_stock', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
}
