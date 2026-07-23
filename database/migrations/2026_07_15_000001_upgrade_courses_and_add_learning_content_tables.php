<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }

            if (! Schema::hasColumn('courses', 'short_description')) {
                $table->text('short_description')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('courses', 'full_description')) {
                $table->longText('full_description')->nullable()->after('short_description');
            }

            if (! Schema::hasColumn('courses', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('full_description');
            }

            if (! Schema::hasColumn('courses', 'programming_language')) {
                $table->string('programming_language')->nullable()->index()->after('thumbnail');
            }

            if (! Schema::hasColumn('courses', 'difficulty_level')) {
                $table->string('difficulty_level')->default('Beginner')->index()->after('programming_language');
            }

            if (! Schema::hasColumn('courses', 'status')) {
                $table->string('status')->default('draft')->index()->after('difficulty_level');
            }

            if (! Schema::hasColumn('courses', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }
        });

        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('order_number')->default(1);
            $table->timestamps();

            $table->index(['course_id', 'order_number']);
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('course_sections')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('lesson_content')->nullable();
            $table->longText('source_code')->nullable();
            $table->longText('expected_output')->nullable();
            $table->longText('explanation')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('order_number')->default(1);
            $table->string('status')->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['course_id', 'section_id', 'order_number']);
        });

        Schema::create('lesson_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('source_code')->nullable();
            $table->longText('expected_output')->nullable();
            $table->longText('explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('role_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('previous_role');
            $table->string('new_role');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_change_logs');
        Schema::dropIfExists('lesson_examples');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('course_sections');

        Schema::table('courses', function (Blueprint $table) {
            $columns = [
                'slug',
                'short_description',
                'full_description',
                'thumbnail',
                'programming_language',
                'difficulty_level',
                'status',
                'created_by',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
