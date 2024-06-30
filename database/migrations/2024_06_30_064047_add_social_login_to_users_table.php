<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('id');
            $table->string('provider_id')->nullable()->after('provider');
            $table->string('avatar')->nullable()->after('remember_token');
            $table->string('password')->nullable()->change();
            $table->string('cellphone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'provider_id', 'provider']);
            $table->string('password')->nullable(false)->change();
            $table->string('cellphone')->nullable(false)->change();
        });
    }
};
