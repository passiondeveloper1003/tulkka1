<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class AddAgoraToSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `sessions` MODIFY COLUMN `session_api` enum('local','big_blue_button','zoom','agora') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' AFTER `zoom_start_link`");

            $table->text('agora_settings')->nullable()->after('moderator_secret');
        });
    }
}
