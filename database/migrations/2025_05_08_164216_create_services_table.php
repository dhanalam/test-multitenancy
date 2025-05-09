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
        Schema::create('services', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('countries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type', 100);
            $table->string('image', 200)->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedTinyInteger('order_no')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
