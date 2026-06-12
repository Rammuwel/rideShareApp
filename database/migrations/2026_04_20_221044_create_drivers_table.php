<?php

use App\Models\User;
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
        Schema::create('drivers', function (Blueprint $table) {
           $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('vehicle_model');
            $table->string('vahical_plate');
            $table->string('profile_photo_path')->nullable();
            $table->string('status')->default('active');
            $table->decimal('rating', 2, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
