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
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->foreignId('type_id')->constrained('types', 'type_id')->onDelete('restrict')->onUpdate('cascade'); // Menautkan ke 'type_id' di 'types'
            $table->foreignId('unit_id')->constrained('units', 'unit_id')->onDelete('restrict')->onUpdate('cascade'); // Menautkan ke 'unit_id' di 'units'
            $table->string('item_name');
            $table->integer('amount');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
