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
        Schema::create('note', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_user');
            $table->foreign('id_user')->references('id')->on('users');

            $table->string('name');
            
            $table->text('text');
            $table->text('files')->default(json_encode(
                [
                    'image' => [],
                    'audio' => [],
                    'video' => []
                ]
            ));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note');
    }
};
