<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', function () {
    return view('Listado de Productos (get)');
});

Route::post('/productos', function () {
    return view('Almacenando Productos (post)');
});

Route::put('/productos/{id}', function ($id) {
    return view('Actualizar Productos' . $id);
});

//Parametros
Route::get('saludo/{name}/{apodo?}', function ($name, $apodo = null) {
	//Poner la primera letra en mayuscula
    $nombre=ucfirst($nombre);
    //Validar si tiene un apodo
    if($apodo){
    	return "Bienvenido {$nombre}, tu apodo es {$apodo}";
    }else{
    	return "Bienvenido {$nombre}";
    }
});
