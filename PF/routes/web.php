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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('table-list', function () {
		return view('pages.table_list');
	})->name('table');

	Route::get('typography', function () {
		return view('pages.typography');
	})->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('rtl-support', function () {
		return view('pages.language');
	})->name('language');

	Route::get('upgrade', function () {
		return view('pages.upgrade');
	})->name('upgrade');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});




///////////////////// RUTAS PARA LOS enfermedadS
Route::get('/enfermedads', function () {
    return view('componentes.componente_enfermedads');
})->name('enfermedads');

Route::get('/enfermedads/obtenerEnfermedads', 'EnfermedadsController@getAll');

Route::get('/enfermedads/buscarEnfermedad/{id}', 'EnfermedadsController@getEnfermedad');

Route::post('/enfermedads/agregarEnfermedad', 'EnfermedadsController@addEnfermedad');

Route::patch('/enfermedads/editarEnfermedad', 'EnfermedadsController@updateEnfermedad');

Route::delete('/enfermedads/eliminarEnfermedad/{id}', 'EnfermedadsController@deleteEnfermedad');


////////////////////////////// RUTAS PARA LOS medicamentoS

Route::get('/medicamentos', function () {
    return view('componentes.componente_medicamentos');
})->name('medicamentos');

Route::get('/medicamentos/obtenerMedicamentos', 'MedicamentosController@getAll');

Route::get('/medicamentos/buscarMedicamento/{id}', 'MedicamentosController@getMedicamento');

Route::post('/medicamentos/agregarMedicamento', 'MedicamentosController@addMedicamento');

Route::patch('/medicamentos/editarMedicamento', 'MedicamentosController@updateMedicamento');

Route::delete('/medicamentos/eliminarMedicamento/{id}', 'MedicamentosController@deleteMedicamento');


////////////////////////////// RUTAS PARA LOS servicioS

Route::get('/servicios', function () {
    return view('componentes.componente_servicios');
})->name('servicios');

Route::get('/servicios/obtenerServicios', 'ServiciosController@getAll');

Route::get('/servicios/buscarServicio/{id}', 'ServiciosController@getServicio');

Route::post('/servicios/agregarServicio', 'ServiciosController@addServicio');

Route::patch('/servicios/editarServicio', 'ServiciosController@updateServicio');

Route::delete('/servicios/eliminarServicio/{id}', 'ServiciosController@deleteServicio');


Route::get('/alergias', function () {
    return view('componentes.componente_alergias');
})->name('alergias');

Route::get('/pacientes', function () {
    return view('componentes.componente_pacientes');
})->name('pacientes');

Route::get('/alergias/obtenerAlergias', 'AlergiasController@getAll');

Route::get('/alergias/buscarAlergia/{id}', 'AlergiasController@getAlergia');

Route::post('/alergias/agregarAlergia', 'AlergiasController@addAlergia');

Route::patch('/alergias/editarAlergia', 'AlergiasController@updateAlergia');

Route::delete('/alergias/eliminarAlergia/{id}', 'AlergiasController@deleteAlergia');


Route::get('registro', function () {
	return view('componentes.componente_registro');
})->name('registro');

//--------RUTAS PACIENTE
        //Ruta para obtener todos los Pacientes del consultorio
        Route::get('/pacientes/obtenerPacientes', 'PacientesController@getPacientesConsultorio');

        //Ruta para obtener un paciente en especifico
        Route::get('/pacientes/buscarPaciente/{id}', 'PacientesController@buscarPaciente');

        Route::get('/pacientes/datos', 'PacientesController@pacienteConsultorio');

        //Ruta de form para registrar un paciente
        Route::post('/pacientes/agregarPaciente', 'PacientesController@crearPaciente');

        //Ruta de form para actualizar un paciente
        Route::patch('/pacientes/editarPaciente', 'PacientesController@actualizarPaciente');

        //Ruta para eliminar un paciente en especifico
        Route::delete('/pacientes/eliminarPaciente/{id}', 'PacientesController@eliminarPaciente');

        //Padecimientos para Pacientes
        Route::get('/pacientes/obtenerPadecimientosPaciente', 'PacientesController@getPadecimientos');

        //Ruta para obtener todos los Pacientes por consultorio sin importar consultorio
        Route::get('/pacientes/pacientesXconsultorio', 'PacientesController@pacienteConsultorio');

        //Ruta para obtener todos los Pacientes sin importar consultorio
        Route::get('/pacientes/totalAdmin', 'PacientesController@cantidadPacientesAll');

    //-------- RUTAS PADECIMIENTOS
        //Obtener los padeimientos del paciente especificado
        Route::get('/padecimiento/obtener/{id}', 'PadecimientosController@getPadecimientosPaciente');

        //Ruta para guardar un padecimiento
        Route::post('/padecimiento/crear', 'PadecimientosController@createPadecimiento');

        Route::patch('/padecimiento/actualizarPadecimiento', 'PadecimientosController@updatePadecimiento');

        Route::get('/padecimiento/buscarPadecimientoPaciente/{id}', 'PadecimientosController@buscarPadecimiento');

        Route::delete('/padecimiento/eliminar/{id}', 'PadecimientosController@deletePadecimiento');

        //--------------RUTAS DE ALERGIAS DE PACIENTES
        //Alergias para Pacientes
        Route::get('/pacientes/obtenerAlergiasPaciente/{id}', 'AlergiaController@getAlergiasPaciente');

        Route::post('/pacientes/crearAlergiasPaciente', 'AlergiaController@crearAlergia');

        Route::patch('/pacientes/actualizarAlergiasPaciente', 'AlergiaController@updateAlergia');

        Route::get('/pacientes/buscarAlergiaPaciente/{id}', 'AlergiaController@buscarAlergia');

        Route::delete('/pacientes/eliminarAlergiaPaciente/{id}', 'AlergiaController@deleteAlergia');