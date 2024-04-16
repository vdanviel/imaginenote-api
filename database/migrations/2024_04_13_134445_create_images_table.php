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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_note');
            $table->foreign('id_note')->on('note')->references('id');

            $table->string('gname')->nullable(true);
            $table->string('appname')->nullable(false);
            $table->string('size')->nullable(true);
            $table->string('x')->nullable(true);
            $table->string('y')->nullable(true);
            $table->string('w')->nullable(true);
            $table->string('h')->nullable(true);            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
