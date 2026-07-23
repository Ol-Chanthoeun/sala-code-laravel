<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('programming_languages')) {
            Schema::create('programming_languages', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->string('difficulty')->default('Beginner')->index();
                $table->unsignedSmallInteger('estimated_time')->default(60);
                $table->string('status')->default('published')->index();
                $table->unsignedInteger('order_number')->default(1);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('quiz_categories')) {
            Schema::create('quiz_categories', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('programming_language_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->string('difficulty')->default('Easy')->index();
                $table->string('status')->default('published')->index();
                $table->unsignedInteger('order_number')->default(1);
                $table->timestamps();

                $table->unique(['programming_language_id', 'slug']);
                $table->index(['programming_language_id', 'order_number']);
            });
        }

        if (! Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('programming_language_id')->constrained()->cascadeOnDelete();
                $table->foreignId('quiz_category_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->string('difficulty')->default('Easy')->index();
                $table->unsignedSmallInteger('estimated_time')->default(15);
                $table->unsignedSmallInteger('passing_score')->default(60);
                $table->string('status')->default('published')->index();
                $table->unsignedInteger('order_number')->default(1);
                $table->timestamps();

                $table->unique(['quiz_category_id', 'slug']);
                $table->index(['programming_language_id', 'quiz_category_id', 'order_number'], 'quizzes_lang_cat_order_idx');
            });
        }

        if (! Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
                $table->longText('question');
                $table->longText('explanation')->nullable();
                $table->string('difficulty')->default('Easy')->index();
                $table->unsignedSmallInteger('points')->default(1);
                $table->unsignedInteger('order_number')->default(1);
                $table->unsignedBigInteger('correct_choice_id')->nullable();
                $table->timestamps();

                $table->index(['quiz_id', 'order_number']);
            });
        }

        if (! Schema::hasTable('quiz_choices')) {
            Schema::create('quiz_choices', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
                $table->string('choice_text');
                $table->boolean('is_correct')->default(false)->index();
                $table->unsignedInteger('order_number')->default(1);
                $table->timestamps();

                $table->index(['quiz_question_id', 'order_number']);
            });
        }

        if (! Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->string('status')->default('in_progress')->index();
                $table->unsignedInteger('score')->default(0);
                $table->unsignedInteger('total_points')->default(0);
                $table->unsignedInteger('correct_count')->default(0);
                $table->unsignedInteger('incorrect_count')->default(0);
                $table->unsignedInteger('time_used')->default(0);
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('quiz_attempt_answers')) {
            Schema::create('quiz_attempt_answers', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
                $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
                $table->foreignId('quiz_choice_id')->nullable()->constrained()->nullOnDelete();
                $table->boolean('is_correct')->default(false);
                $table->unsignedSmallInteger('points_awarded')->default(0);
                $table->timestamps();

                $table->unique(['quiz_attempt_id', 'quiz_question_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_choices');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('quiz_categories');
        Schema::dropIfExists('programming_languages');
    }
};
