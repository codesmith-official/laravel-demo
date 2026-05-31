<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('first_name')->default('')->after('id')->index();
            $table->string('last_name')->default('')->after('first_name')->index();
            $table->string('phone_number', 30)->nullable()->after('email')->index();
            $table->string('status', 20)->default('inactive')->after('password')->index();
            $table->string('profile_photo_path')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('profile_photo_path')->index();
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone_number',
                'status',
                'profile_photo_path',
                'last_login_at',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
