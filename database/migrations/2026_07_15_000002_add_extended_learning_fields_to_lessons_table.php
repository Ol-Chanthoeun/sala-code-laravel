<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table): void {
            if (! Schema::hasColumn('lessons', 'short_description')) {
                $table->text('short_description')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('lessons', 'code_explanation')) {
                $table->longText('code_explanation')->nullable()->after('explanation');
            }

            if (! Schema::hasColumn('lessons', 'common_mistakes')) {
                $table->longText('common_mistakes')->nullable()->after('code_explanation');
            }

            if (! Schema::hasColumn('lessons', 'tips')) {
                $table->longText('tips')->nullable()->after('common_mistakes');
            }

            if (! Schema::hasColumn('lessons', 'summary')) {
                $table->longText('summary')->nullable()->after('tips');
            }

            if (! Schema::hasColumn('lessons', 'exercise')) {
                $table->longText('exercise')->nullable()->after('summary');
            }

            if (! Schema::hasColumn('lessons', 'quiz')) {
                $table->json('quiz')->nullable()->after('exercise');
            }

            if (! Schema::hasColumn('lessons', 'difficulty_level')) {
                $table->string('difficulty_level')->default('Beginner')->index()->after('quiz');
            }

            if (! Schema::hasColumn('lessons', 'estimated_learning_time')) {
                $table->unsignedSmallInteger('estimated_learning_time')->default(20)->after('difficulty_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table): void {
            $columns = [
                'short_description',
                'code_explanation',
                'common_mistakes',
                'tips',
                'summary',
                'exercise',
                'quiz',
                'difficulty_level',
                'estimated_learning_time',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('lessons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
