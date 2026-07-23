<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('role')->nullable()->index();
            $table->string('action')->index();
            $table->string('module')->index();
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('route_name')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
