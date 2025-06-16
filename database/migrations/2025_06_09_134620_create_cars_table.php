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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Owner
            $table->string('make')->nullable(); // BMW
            $table->string('model')->nullable(); // 320
            $table->integer('year')->nullable();
            $table->string('body_type')->nullable(); // Sedan
            $table->string('transmission')->nullable(); // Manual
            $table->string('fuel_type')->nullable(); // Petrol, Diesel, Electric
            $table->integer('mileage')->nullable(); // in kilometers
            $table->decimal('price', 10, 2)->nullable();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true); // listing status
            $table->boolean('show_email')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_reported')->default(false);
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('cars');
    }
};
