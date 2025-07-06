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
        Schema::create('test_assesments', function (Blueprint $table) {
            $table->id();
            $table->enum('for_whom', ['takım','bireysel']);
            $table->string('age_group');
            //$table->string('age_group_description');
            $table->text('34-38');
            $table->text('38-42');
            $table->text('43-48');
            $table->text('49-54');
            $table->text('55-59');
            $table->text('60-62');
            $table->text('63-65');
            $table->text('66-68');
            $table->text('69-72');
            $table->text('73+');
            //$table->string('speed_description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_assesments');
    }
};
