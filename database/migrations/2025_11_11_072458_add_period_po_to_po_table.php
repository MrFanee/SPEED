<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodPoToPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('po_table', function (Blueprint $table) {
            $table->string('period')->nullable()->after('id'); // bisa diganti posisi after sesuai kebutuhan
            $table->string('purchase_group')->nullable()->after('po_number');
            $table->string('delivery_date')->nullable()->after('qty_outstanding');
        });
    }

    public function down(): void
    {
        Schema::table('po_table', function (Blueprint $table) {
            $table->dropColumn(['period', 'purchase_group', 'delivery_date']);
        });
    }
}
