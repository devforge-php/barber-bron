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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Qaysi barberga yozilgan
            $table->string('name'); // Foydalanuvchi ismi
            $table->string('phone'); // Foydalanuvchi raqami
            $table->unsignedTinyInteger('rating'); // Reyting
            $table->text('comment')->nullable(); // Sharh matni
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
