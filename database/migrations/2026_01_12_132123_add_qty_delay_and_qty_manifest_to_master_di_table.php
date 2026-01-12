<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyDelayAndQtyManifestToMasterDiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_di', function (Blueprint $table) {
            $table->integer('qty_delay')->after('balance');
            $table->integer('qty_manifest')->after('qty_delay');
        });
    }

    public function down()
    {
        Schema::table('master_di', function (Blueprint $table) {
            $table->dropColumn(['qty_delay', 'qty_manifest']);
        });
    }
}
