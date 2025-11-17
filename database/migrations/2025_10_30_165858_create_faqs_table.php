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
       Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->nullable()->default(null);
            $table->text('question')->nullable()->default(null);
            $table->text('answer')->nullable()->default(null);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order_no')->nullable();
            $table->softDeletes(); // ✅ adds deleted_at
            $table->timestamps(); // ✅ adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
