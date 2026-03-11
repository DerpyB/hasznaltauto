<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code', 6)->nullable()->after('password');
            $table->timestamp('verification_expires_at')->nullable()->after('verification_code');
            $table->boolean('is_verified')->default(false)->after('verification_expires_at');
            $table->string('reset_token', 64)->nullable()->after('is_verified');
            $table->timestamp('reset_expires_at')->nullable()->after('reset_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_expires_at', 'is_verified', 'reset_token', 'reset_expires_at']);
        });
    }
};