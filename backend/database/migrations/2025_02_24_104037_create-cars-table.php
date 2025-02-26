<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', static function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('model', 100);
            $table->string('brand', 100);
            $table->string('license_plate', 20)->unique();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
