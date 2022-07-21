<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->integer('vessel_id');
            $table->string('code', 100); //Again, maybe someone tries to iput an essay of a name
            $table->dateTime('start'); 
            $table->dateTime('end')->nullable(); 
            $table->string('status', 20); //if this is not an enum i run out of time
            $table->decimal('revenues', 9, 2); //I gave an extra digit in case they hit a mil. if they hit 10 mil thay can afford the deluxe package
            $table->decimal('expenses', 9, 2)->nullable(); 
            $table->decimal('profit', 9, 2)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voyages');
    }
};
