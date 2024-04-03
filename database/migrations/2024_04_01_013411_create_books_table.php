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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('ISBN', 13)->unique();
            $table->unsignedBigInteger('genre_id')->default(1)->onDelete('cascade');
            $table->unsignedBigInteger('editorial_id')->default(1)->onDelete('cascade');
            $table->unsignedBigInteger('author_id')->default(1)->onDelete('cascade');
            $table->timestamps();

            //Foreign Keys
            $table->foreign('genre_id')->references('id')->on('genres');
            $table->foreign('editorial_id')->references('id')->on('editorials');
            $table->foreign('author_id')->references('id')->on('authors');
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
