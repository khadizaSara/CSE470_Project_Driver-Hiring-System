<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('driver_id')->constrained()->onDelete('cascade');
        $table->string('pickup_location');
        $table->string('destination');
        $table->enum('service_type', ['regular', 'urgent']);
        $table->string('status')->default('pending'); // For booking status
        $table->timestamps();
    });
}


   
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
