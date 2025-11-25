<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorUpdatedAtToMasterStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_stock', function (Blueprint $table) {
            $table->dateTime('vendor_updated_at')->nullable()->after('updated_at');
        });
    }

    public function down()
    {
        Schema::table('master_stock', function (Blueprint $table) {
            $table->dropColumn('vendor_updated_at');
        });
    }
}
