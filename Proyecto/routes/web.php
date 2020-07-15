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

///////////////////// RUTAS PARA LOS enfermedadS
Route::get('/enfermedads', function () {
    return view('componentes.componente_enfermedads');
});

Route::get('/enfermedads/obtenerEnfermedads', 'EnfermedadsController@getAll');

Route::get('/enfermedads/buscarEnfermedad/{id}', 'EnfermedadsController@getEnfermedad');

Route::post('/enfermedads/agregarEnfermedad', 'EnfermedadsController@addEnfermedad');

Route::patch('/enfermedads/editarEnfermedad', 'EnfermedadsController@updateEnfermedad');

Route::delete('/enfermedads/eliminarEnfermedad/{id}', 'EnfermedadsController@deleteEnfermedad');


////////////////////////////// RUTAS PARA LOS medicamentoS

Route::get('/', function () {
    return view('componentes.componente_medicamentos');
});

Route::get('/medicamentos/obtenerMedicamentos', 'MedicamentosController@getAll');

Route::get('/medicamentos/buscarMedicamento/{id}', 'MedicamentosController@getMedicamento');

Route::post('/medicamentos/agregarMedicamento', 'MedicamentosController@addMedicamento');

Route::patch('/medicamentos/editarMedicamento', 'MedicamentosController@updateMedicamento');

Route::delete('/medicamentos/eliminarMedicamento/{id}', 'MedicamentosController@deleteMedicamento');


////////////////////////////// RUTAS PARA LOS servicioS

Route::get('/servicios', function () {
    return view('componentes.componente_servicios');
});

Route::get('/servicios/obtenerServicios', 'ServiciosController@getAll');

Route::get('/servicios/buscarServicio/{id}', 'ServiciosController@getServicio');

Route::post('/servicios/agregarServicio', 'ServiciosController@addServicio');

Route::patch('/servicios/editarServicio', 'ServiciosController@updateServicio');

Route::delete('/servicios/eliminarServicio/{id}', 'ServiciosController@deleteServicio');









Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
