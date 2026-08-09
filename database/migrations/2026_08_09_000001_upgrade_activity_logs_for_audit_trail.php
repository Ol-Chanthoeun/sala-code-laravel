<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            $table->string('user_email')->nullable()->after('user_name')->index();
            $table->string('target_type')->nullable()->after('module');
            $table->unsignedBigInteger('target_id')->nullable()->after('target_type');
            $table->string('target_name')->nullable()->after('target_id')->index();
            $table->json('old_values')->nullable()->after('description');
            $table->json('new_values')->nullable()->after('old_values');
            $table->string('device')->nullable()->after('browser');
            $table->string('severity', 20)->default('normal')->after('device')->index();
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            $table->dropIndex(['target_type', 'target_id']);
            $table->dropColumn(['user_email','target_type','target_id','target_name','old_values','new_values','device','severity']);
        });
    }
};
