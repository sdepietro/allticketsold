<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->text('nombres');
            $table->text('apellidos');
            $table->text('correo');
            $table->text('url');
            $table->text('otros1')->nullable(); // Nullable si no es obligatorio
            $table->text('otros2')->nullable();
            $table->text('otros3')->nullable();
            $table->text('otros4')->nullable();
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
        Schema::dropIfExists('vendedores');
    }
}
