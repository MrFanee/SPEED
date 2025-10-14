<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_table', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->integer('record_id');
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
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
        Schema::dropIfExists('log_table');
    }
}
