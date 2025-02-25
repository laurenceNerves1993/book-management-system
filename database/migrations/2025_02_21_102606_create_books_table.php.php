<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    { Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->unsignedBigInteger('author_id'); // Foreign key
        $table->unsignedBigInteger('category_id'); // Foreign key
        $table->text('description')->nullable();
        $table->string('cover_image', 255)->nullable(); // Added this line
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

        // Foreign key constraints
        $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
