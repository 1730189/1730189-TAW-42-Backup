<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Empleado;

//COMANDOS
/*
1. Crear Migracion:
php artisan make:migration Nombre

2. Crear Modelo
php artisan make:model Nombre
*/

//Listar Empleados
Route::get('empleados', function(){
	$empleados = Empleado::get();
	return $empleados;
});

//Ruta para guardar nuevos empleados y recibir data
Route::post('empleados'function(Request $request){
	//Verificamos que los atos enviados se reciban bien para guardar en la bd,
	//return 'Guardando Empleados'
	
	//Retorna todos los valores del array del form
	//return $request->all();

	$request->validate([
		'nombres' => 'required|max:50',
		'apellidos' => 'required|max:50',
		'cedula' => 'required|max:20',
		'email' => 'required|max:50|email|unique:empleados',
		'lugar_nacimiento' => 'required|max:50',
		'estado_civil' => 'required|max:50',
		'telefono' => 'required|numeric|max:50'
	]);


	$empleados = new Empleado;
	$empleado->nombres = $request->input('nombres');
	$empleado->apellidos = $request->input('apellidos');
	$empleado->cedula = $request->input('cedula');
	$empleado->email = $request->input('email');
	$empleado->lugar_nacimiento = $request->input('lugar_nacimiento');
	$empleado->sexo = $request->input('sexo');
	$empleado->estado_civil = $request->input('estado_civil');
	$empleado->telefono = $request->input('telefono');
	$empleados->save();
	return "Usuario Creado";
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
