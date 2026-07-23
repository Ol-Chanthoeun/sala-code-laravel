<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tests', 'course_name')) {
            Schema::table('tests', function (Blueprint $table) {
                $table->string('course_name')->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tests', 'course_name')) {
            Schema::table('tests', function (Blueprint $table) {
                $table->dropColumn('course_name');
            });
        }
    }
};
