<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EditCertificatesTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificates_templates', function (Blueprint $table) {
            DB::statement("ALTER TABLE `certificates_templates`
                    DROP COLUMN `position_x`,
                    DROP COLUMN `position_y`,
                    DROP COLUMN `font_size`,
                    DROP COLUMN `text_color`");

            $table->enum('type', ['quiz', 'course'])->after('images');
        });

        Schema::table('certificate_template_translations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `certificate_template_translations`
                        DROP COLUMN `rtl`,
                        MODIFY COLUMN `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER `title`");
        });
    }
}
