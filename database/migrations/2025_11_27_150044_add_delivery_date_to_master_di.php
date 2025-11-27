<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryDateToMasterDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_di', function (Blueprint $table) {
            $table->date('delivery_date')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('master_di', function (Blueprint $table) {
            $table->dropColumn('delivery_date');
        });
    }
}
