<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
{
    Schema::create('drivers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('age');
        $table->integer('experience');
        $table->float('rating');
        $table->enum('type', ['regular', 'urgent', 'both']);
        $table->timestamps();
    });
}


   
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
