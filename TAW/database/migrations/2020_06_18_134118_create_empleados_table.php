<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facedes\Schema;

class CreateEmpleadosTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up(){
		Schema::create('empleados', function (Blueprint $table){
			$table->id();
			$table->string('nombres');
			$table->string('apellidos');
			$table->integer('cedula');
			$table->string('email')->unique();
			$table->string('lugar_nacimiento');
			$table->enum('sexo',['masculino', 'femenino', 'no definido']);
			$table->enum('estado_civil',['soltero', 'casado']);
			$table->integer('telefono');
			$table->timestamps();
		});
	}

	public function down(){
		Schema::dropIfExist('empleados');
	}
}

?>