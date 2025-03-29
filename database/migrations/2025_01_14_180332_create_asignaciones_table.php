<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('idobra'); // Número largo para idobra
            $table->bigInteger('idvendedor'); // Número largo para idvendedor
            $table->text('otro1')->nullable(); // Columna para otros datos (opcional)
            $table->text('otro2')->nullable(); 
            $table->text('otro3')->nullable(); 
            $table->text('otro4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignaciones');
    }
}
