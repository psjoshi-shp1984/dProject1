<?php

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
        Schema::create('static_page', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->nullable();
            $table->string('page_slug')->nullable();
            $table->string('image')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_hover_text')->nullable();
            $table->text('page_descriptions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps(); // creates created_at and updated_at
            $table->softDeletes(); // creates deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_page');
    }
};
