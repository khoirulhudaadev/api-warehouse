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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_id');
            $table->string('item_name', 100);
            $table->integer('item_id');
            $table->string('management_in', 60);
            $table->string('management_out', 60);
            $table->string('type_name', 60);
            $table->integer('type_id');
            $table->string('unit_name', 60);
            $table->integer('unit_id');
            $table->integer('amount');
            $table->string('image');
            $table->string('image_public_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
