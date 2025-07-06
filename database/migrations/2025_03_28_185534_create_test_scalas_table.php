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
        Schema::create('test_scalas', function (Blueprint $table) {
            $table->id();
            $table->string('age_group');
            $table->unsignedBigInteger('under1');
            $table->unsignedBigInteger('under2');
            $table->unsignedBigInteger('mid1');
            $table->unsignedBigInteger('mid2');
            $table->unsignedBigInteger('ideal1');
            $table->unsignedBigInteger('ideal2');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_scalas');
    }
};
