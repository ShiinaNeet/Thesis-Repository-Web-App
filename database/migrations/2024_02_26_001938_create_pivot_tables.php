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
        Schema::create('thesis_author', function (Blueprint $table) {
            $table->foreignId('thesis_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->primary(['thesis_id', 'author_id']);
        });

        Schema::create('thesis_keyword', function (Blueprint $table) {
            $table->foreignId('thesis_id')->constrained()->onDelete('cascade');
            $table->foreignId('keyword_id')->constrained()->onDelete('cascade');
            $table->primary(['thesis_id', 'keyword_id']);
        });

        Schema::create('thesis_category', function (Blueprint $table) {
            $table->foreignId('thesis_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->primary(['thesis_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_author');
        Schema::dropIfExists('thesis_keyword');
        Schema::dropIfExists('thesis_category');
    }
};
