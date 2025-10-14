<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_di', function (Blueprint $table) {
            $table->id();
            $table->integer('qty_plan');
            $table->integer('qty_delivery');
            $table->integer('balance');
            $table->foreignId('po_id')->constrained('po_table')->onDelete('restrict');
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
        Schema::dropIfExists('master_di');
    }
}
