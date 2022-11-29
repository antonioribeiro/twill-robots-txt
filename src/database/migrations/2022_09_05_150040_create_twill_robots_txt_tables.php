<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwillRobotsTxtTables extends Migration
{
    public function up(): void
    {
        Schema::create('twill_robots_txt', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->string('domain')->nullable();

            $table->text('protected')->nullable();

            $table->text('unprotected')->nullable();
        });

        Schema::create('twill_robots_txt_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'twill_robots_txt', 'twill_robots_txt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twill_robots_txt_revisions');
        Schema::dropIfExists('twill_robots_txt');
    }
}
