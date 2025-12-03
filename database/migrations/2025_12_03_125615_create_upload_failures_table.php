<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadFailuresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('upload_failures', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->longText('raw_data');
            $table->string('error_message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('upload_failures');
    }
}
