<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password')->index();
            $table->string('status')->default('active')->after('role')->index();
            $table->string('google_id')->nullable()->unique()->after('status');
            $table->string('avatar')->nullable()->after('google_id');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropColumn([
                'role',
                'status',
                'google_id',
                'avatar',
                'last_login_at',
            ]);
        });
    }
};
