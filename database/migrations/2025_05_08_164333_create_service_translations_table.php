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
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('lang_id')->constrained('languages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->unique(['service_id', 'lang_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};
