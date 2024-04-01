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
            $table->string('ISBN', 13);
            $table->unsignedBigInteger('genre');
            $table->unsignedBigInteger('editorial');
            $table->unsignedBigInteger('author');
            $table->timestamps();

            //Foreign Keys
            $table->foreign('genre')->references('id')->on('genres');
            $table->foreign('editorial')->references('id')->on('editorials');
            $table->foreign('author')->references('id')->on('authors');
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
