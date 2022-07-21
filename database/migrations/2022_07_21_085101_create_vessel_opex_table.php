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
        Schema::create('vessel_opex', function (Blueprint $table) {
            $table->id();
            $table->integer('vessel_id');
            $table->date('date');
            $table->decimal('expenses', 8, 2); //this is daily expenses, 6(integer) digits are too many already
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vessel_opex');
    }
};
