<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('countries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('countries')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};
