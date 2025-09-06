<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
        Schema::table('drivers', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0);
    });
}

    public function down()
{
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn('average_rating');
    });
}

};
