<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateUsernameCollationInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY username VARCHAR(255)
            COLLATE utf8mb4_bin 
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY username VARCHAR(255)
            COLLATE utf8mb4_unicode_ci 
            NOT NULL
        ");
    }
}
