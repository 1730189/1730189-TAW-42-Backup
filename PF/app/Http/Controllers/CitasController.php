<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Paciente;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\add;

class CitasController extends Controller
{
    //Funcion obtener citas y mostrar en primera pantalla
    function allCitasDoctor(Request $request){
        $doctor = Auth::user();

        $citas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->selectRaw('citas.id, citas.fecha_cita, citas.hora_cita, citas.asunto, DATE_FORMAT(citas.created_at, \'%Y-%m-%d\') as fecha_creacion, concat_ws(" ", paciente.nombre, paciente.apellidos) as paciente')
            ->where('id_medico', '=', $doctor->id)
            ->orderBy('fecha_cita', 'DESC')
            ->paginate(10);

        $allCitas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->selectRaw('citas.id, citas.fecha_cita, citas.hora_cita, citas.asunto, DATE_FORMAT(citas.created_at, \'%Y-%m-%d\') as fecha_creacion, concat_ws(" ", paciente.nombre, paciente.apellidos) as paciente')
            ->where('id_medico', '=', $doctor->id)->get();

        $citasConsultadas = DB::table('citas')
            ->join('consulta', 'citas.id', '=', 'consulta.id_cita')
            ->select('citas.id')
            ->where('id_medico', '=', $doctor->id)
            ->get();

        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('America/Mexico_City'));

        $fecha = $dt->format('Y-m-d');
        $hora = $dt->format('H:i:s');;

        $citasHoy = DB::table('citas')
            ->select('citas.*')
            ->whereRaw('citas.id_medico = '. $doctor->id . ' AND citas.fecha_cita LIKE \'' . $fecha . '\'')
//            ->whereRaw('citas.id_medico = '. $doctor->id_usuario . ' AND citas.fecha_cita LIKE \'' . $fecha . '\' AND citas.hora_cita BETWEEN \''. $hora . '\' AND \'23:59:59\'')
            ->get();

        $citasPendientesHoy = DB::table('citas')
            ->join('consulta', 'citas.id', '=', 'consulta.id_cita')
            ->select('citas.*')
            ->whereRaw('citas.id_medico = '. $doctor->id . ' AND citas.fecha_cita LIKE \'' . $fecha . '\'')
            ->get();

        return [
            'pagination' => [
                'total' => $citas->total(),
                'current_page' => $citas->currentPage(),
                'per_page' => $citas->perPage(),
                'last_page' => $citas->lastPage(),
                'from' => $citas->firstItem(),
                'to' => $citas->lastPage(),
            ],
            'citas' => $citas,
            'citasConsultadas' => $citasConsultadas,
            'fechaActual' => $fecha,
            'horaActual' => $hora,
            'informacion' => [
                'cantCitas' => count($allCitas),
                'cantCitasHoy' => count($citasHoy),
                'cantPendientesCitasHoy' => count($citasPendientesHoy)
            ],
            'citasHoy' => $citasHoy
        ];
    }

    function allPacientes(){
        //Usuario = Doctor || Secretaria
        $usuario = Auth::user();

        $usuario = DB::table('users')
            ->select('users.id_consultorio')
            ->where('id', '=', $usuario->id)
            ->get();

        $usuario = $usuario[0];

        $pacientes = Paciente::where('id_consultorio', '=', $usuario->id_consultorio)->get();

        return $pacientes;
    }

    function consultarHorasDisponibles($dia){
        $citas = Cita::where('fecha_cita', '=', $dia)->get();

        $horas = [];

        $horas = $this->horasDeCita($horas);

        return $this->verificacionHoras($citas, $horas);
    }

    //Funcion para registrar una nueva cita con el doctor
    function crearCitaDoctor(Request $request){
        $this->validarDoctor($request);

        $cita = new Cita();

        $cita = $this->refactorizadorDoctor($request, $cita);

        $cita->save();

        //Retornamos a la lista
        return (['message' => 'Cita creada correctamente']);
    }

    //Funcion para actualizar la cita por parte del doctor
    function updateCitaDoctor(Request $request){
        $this->validarDoctor($request);

        $cita = Cita::findOrfail($request->id);

        $cita = $this->refactorizadorDoctor($request, $cita);

        $cita->save();

        //Retornamos a la lista
        return (['message' => 'Cita actualizada correctamente']);
    }

    //Funcion busqueda de citas y mostrar en primera pantalla
    function busquedaCitasDoctor(Request $request){
        $doctor = Auth::user();

        $citas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->selectRaw('citas.id, citas.fecha_cita, citas.hora_cita, citas.asunto, DATE_FORMAT(citas.created_at, \'%Y-%m-%d\') as fecha_creacion, concat_ws(" ", paciente.nombre, paciente.apellidos) as paciente');

        $consulta = "";
        $cont = 0;
        if($request->asunto != ""){
            $consulta = "citas.asunto LIKE '".$request->asunto."%'";
            $cont = 1;
        }

        if($request->fecha_cita != "" && $request->fecha_final != ""){
            if($cont == 1){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.fecha_cita BETWEEN '".$request->fecha_cita."' AND'".$request->fecha_cita."'";
            $cont = 2;
        }

        if($request->fecha_cita != "" && $cont != 2){
            if($cont == 1 || $cont == 2){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.fecha_cita BETWEEN '".$request->fecha_cita."' AND'".$request->fecha_cita."'";
            $cont = 3;
        }

        if($request->id_paciente != "" && $request->id_paciente != "seleccionarPaciente"){
            if($cont == 1 || $cont == 2 || $cont == 3){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.id_paciente = ". $request->id_paciente;
            $cont = 4;
        }

        if($cont != 0){
            $consulta = $consulta." AND ";
        }

        $consulta = $consulta." citas.id_medico = " . $doctor->id;

        $citas = $citas->whereRaw($consulta)
            ->orderBy('fecha_cita', 'DESC')
            ->paginate(10);

         return [
            'pagination' => [
                'total' => $citas->total(),
                'current_page' => $citas->currentPage(),
                'per_page' => $citas->perPage(),
                'last_page' => $citas->lastPage(),
                'from' => $citas->firstItem(),
                'to' => $citas->lastPage(),
            ],
            'citas' => $citas,
        ];
    }

    function refactorizadorDoctor($request, $cita){
        //Asignamos los valores
        $doctor = Auth::user();

        $cita->asunto = $request->asunto;
        $cita->fecha_cita = $request->fecha_cita;
        $cita->hora_cita = $request->hora_cita;
        $cita->id_medico = $doctor->id;
        $cita->id_paciente = $request->id_paciente;

        //Retornamos la cita para ver si no hay ningun error
        return $cita;
    }

    function validarDoctor($request){
        return $this->validate($request, [
            'asunto' => 'required',
            'fecha_cita' => 'required',
            'hora_cita' => 'required',
            'id_paciente' => 'required|notIn:seleccionarPaciente',
        ],[
            'asunto.required' => 'El asunto de la cita es requerido',
            'fecha_cita.required' => 'La fecha de la cita es requerido',
            'hora_cita.required' => 'La hora de la cita es requerida',
            'id_paciente.notIn' => 'El paciente es requerido',
            'id_paciente.required' => 'El paciente es requerido',
        ]);
    }

    // FUNCIONES POR PARTE DE SECRETARIA  -------------------------------------------------
    function allCitasSecretaria(Request $request){
        $secretaria = Auth::user();

        $secretaria = DB::table('users')
            ->select('users.id_consultorio')
            ->where('id', '=', $secretaria->id)
            ->get();

        $secretaria = $secretaria[0];

        $citas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->join('users', 'citas.id_medico', '=', 'users.id')
            ->selectRaw('citas.fecha_cita, citas.hora_cita, citas.asunto, DATE_FORMAT(citas.created_at, \'%Y-%m-%d\') as fecha_creacion, concat_ws(" ", paciente.nombre, paciente.apellidos) as paciente, concat_ws(" ", users.nombre, users.apellidos) as doctor')
            ->whereRaw('users.id_consultorio = ' . $secretaria->id_consultorio)
            ->orderBy('fecha_cita','DESC')
            ->paginate(10);

        $citasConsultadas = DB::table('citas')
            ->join('consulta', 'citas.id', '=', 'consulta.id_cita')
            ->join('users', 'citas.id', '=', 'users.id')
            ->select('citas.id')
            ->whereRaw('users.id_consultorio = ' . $secretaria->id_consultorio)
            ->get();

        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('America/Mexico_City'));

        $fecha = $dt->format('Y-m-d');
        $hora = $dt->format('H:i:s');;

        return [
            'pagination' => [
                'total' => $citas->total(),
                'current_page' => $citas->currentPage(),
                'per_page' => $citas->perPage(),
                'last_page' => $citas->lastPage(),
                'from' => $citas->firstItem(),
                'to' => $citas->lastPage(),
            ],
            'citas' => $citas,
            'citasConsultadas' => $citasConsultadas,
            'fechaActual' => $fecha,
            'horaActual' => $hora
        ];
    }

    //Funcion para obtener a los doctores de ese consultorio de la secretaria
    function allDoctoresSecretaria(){
        $secretaria = Auth::user();

        $secretaria = DB::table('users')
            ->select('users.id_consultorio')
            ->where('id', '=', $secretaria->id)
            ->get();

        $secretaria = $secretaria[0];

        $doctores = DB::table('users')
            ->select('users.*')
            ->whereRaw('users.id_consultorio = ' . $secretaria->id_consultorio . ' AND users.id_tipo_usuario = 1')
            ->get();

        return $doctores;
    }

    //Funcion para obtener las horas dispibles, dependiendo del dia y el doctor
    function consultarHorasDisponiblesConsultorio($dia, $doctor){
        $citas = Cita::where('fecha_cita', '=', $dia, 'AND', 'id_medico', '=', $doctor)->get();

        $horas = [];

        $horas = $this->horasDeCita($horas);

        return $this->verificacionHoras($citas, $horas);
    }

    //Funcion para agendar cita modo secretaria
    function crearCitaSecretaria(Request $request){
        $this->validarSecretaria($request);

        $cita = new Cita();

        $cita = $this->refactorizadorSecretaria($request, $cita);

        $cita->save();

        //Retornamos a la lista
        return (['message' => 'Cita creada correctamente']);
    }

    //Funcion para actualizar cita modo secretaria
    function updateCitaSecretaria(Request $request){
        $this->validarSecretaria($request);

        $cita = Cita::findOrfail($request->id);

        $cita = $this->refactorizadorSecretaria($request, $cita);

        $cita->save();

        //Retornamos a la lista
        return (['message' => 'Cita actualizada correctamente']);
    }

    //Funcion busqueda de citas y mostrar en primera pantalla
    function busquedaCitasSecretaria(Request $request){
        $doctor = Auth::user();

        $citas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->selectRaw('citas.id, citas.fecha_cita, citas.hora_cita, citas.asunto, DATE_FORMAT(citas.created_at, \'%Y-%m-%d\') as fecha_creacion, concat_ws(" ", paciente.nombre, paciente.apellidos) as paciente');

        $consulta = "";
        $cont = 0;
        if($request->asunto != ""){
            $consulta = "citas.asunto LIKE '".$request->asunto."%'";
            $cont = 1;
        }

        if($request->fecha_cita != "" && $request->fecha_final != ""){
            if($cont == 1){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.fecha_cita BETWEEN '".$request->fecha_cita."' AND'".$request->fecha_cita."'";
            $cont = 2;
        }

        if($request->fecha_cita != "" && $cont != 2){
            if($cont == 1 || $cont == 2){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.fecha_cita BETWEEN '".$request->fecha_cita."' AND'".$request->fecha_cita."'";
            $cont = 3;
        }

        if($request->id_paciente != "" && $request->id_paciente != "seleccionarPaciente"){
            if($cont == 1 || $cont == 2 || $cont == 3){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.id_paciente = ". $request->id_paciente;
            $cont = 4;
        }

        if($request->id_doctor != "" && $request->id_doctor != "seleccioneDoctor"){
            if($cont == 1 || $cont == 2 || $cont == 3 || $cont == 4){
                $consulta = $consulta." AND ";
            }
            $consulta = $consulta . "citas.id_medico = ". $request->id_doctor;
        }

        $citas = $citas->whereRaw($consulta)
            ->orderBy('fecha_cita', 'DESC')
            ->paginate(10);

        return [
            'pagination' => [
                'total' => $citas->total(),
                'current_page' => $citas->currentPage(),
                'per_page' => $citas->perPage(),
                'last_page' => $citas->lastPage(),
                'from' => $citas->firstItem(),
                'to' => $citas->lastPage(),
            ],
            'citas' => $citas,
        ];
    }

    function validarSecretaria($request){
        return $this->validate($request, [
            'asunto' => 'required',
            'fecha_cita' => 'required',
            'hora_cita' => 'required',
            'id_paciente' => 'required|notIn:seleccionarPaciente',
            'id_doctor' => 'required|notIn:seleccioneDoctor',
        ],[
            'asunto.required' => 'El asunto de la cita es requerido',
            'fecha_cita.required' => 'La fecha de la cita es requerido',
            'hora_cita.required' => 'La hora de la cita es requerida',
            'id_paciente.notIn' => 'El paciente es requerido',
            'id_paciente.required' => 'El paciente es requerido',
            'id_doctor.notIn' => 'El doctor es requerido',
            'id_doctor.required' => 'El doctor es requerido',
        ]);
    }

    function refactorizadorSecretaria($request, $cita){
        //Asignamos los valores
        $cita->asunto = $request->asunto;
        $cita->fecha_cita = $request->fecha_cita;
        $cita->hora_cita = $request->hora_cita;
        $cita->id_medico = $request->id_doctor;
        $cita->id_paciente = $request->id_paciente;
        //Retornamos la cita para ver si no hay ningun error
        return $cita;
    }

    //Funcion para eliminar la cita por parte del DOCTOR / SECRETARIA / ADMIN
    function deleteCita($id){
        $cita = Cita::findOrfail($id);

        $cita->delete();

        //Retornamos a la lista
        return $cita;
    }

    //Funcion para eliminar cita de la base de datos
    function borrarCita($id){
        $cita = Cita::findOrFail($id)->get()->first();

        $cita->destroy();

        return redirect('/citas/obtenerCitas');
    }

    //Todas las citas admin completo
    function todasCitas(){
        $cita = count(Cita::all());

        $citasConsultadas = count(DB::table('consulta')
            ->join('citas', 'citas.id', '=', 'consulta.id_cita')
            ->select('citas.*')
            ->get());

        return [
            'citasRegistradas' => $cita,
            'citasConsultadas' => $citasConsultadas
        ];
    }

    //Todas la citas que tenga el doctor
    function informacionCitasDoctor(){
        $doctor = Auth::user();

        $citas = Cita::where('id_medico', '=', $doctor->id)->get();

        $citasRegistradas = count($citas);

        $citasConsultadas = DB::table('citas')
            ->join('consulta', 'citas.id', '=', 'consulta.id_cita')
            ->select('citas.*')
            ->where('id_medico', '=', $doctor->id)
            ->get();

        return [
            'citasRegistradas' => $citasRegistradas,
            'citasConsultadas' => count($citasConsultadas)
        ];
    }

    function horasDeCita($horas){
        array_push($horas,['hora'=> '07:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '07:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '08:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '08:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '09:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '09:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '10:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '10:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '11:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '11:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '12:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '12:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '13:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '13:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '14:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '14:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '15:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '15:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '16:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '16:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '17:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '17:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '18:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '18:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '19:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '19:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '20:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '20:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '21:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '21:30:00', 'estado'=> true]);
        array_push($horas,['hora'=> '22:00:00', 'estado'=> true]);
        array_push($horas,['hora'=> '22:30:00', 'estado'=> true]);

        return $horas;
    }

    function verificacionHoras($citas, $horas){
        if(count($citas) == 0){
            return json_encode($horas);
        }

        foreach ($citas as $cita){
            for($i = 0; $i < sizeof($horas); $i++){
                if($cita['hora_cita'] == $horas[$i]['hora']){
                    $horas[$i]['estado'] = false;
                }
            }
        }

        //Retornamos los valores de horas
        return json_encode($horas);
    }

    //Funcion en conjunto para buscar una cita
    function buscarCita($id){
        $cita = Cita::where('id', '=', $id)->get();

        return $cita[0];
    }

    //Funcion para obtener las citas del dia de hoy
    function citasHoy(Request $request){
        $doctor = Auth::user();

        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('America/Mexico_City'));

        $fecha = $dt->format('Y-m-d');
        $hora = $dt->format('H:i:s');

        $citas = DB::table('citas')
            ->join('paciente', 'citas.id_paciente', '=', 'paciente.id')
            ->selectRaw('citas.*, concat_ws(" ", paciente.nombre, paciente.apellidos) as nombre_paciente')
            ->whereRaw('citas.id_medico = '. $doctor->id . ' AND citas.fecha_cita LIKE \'' . $fecha . '\' AND citas.hora_cita BETWEEN \''. $hora . '\' AND \'23:59:59\'')
            ->paginate(6);

        return [
            'pagination' => [
                'total' => $citas->total(),
                'current_page' => $citas->currentPage(),
                'per_page' => $citas->perPage(),
                'last_page' => $citas->lastPage(),
                'from' => $citas->firstItem(),
                'to' => $citas->lastPage(),
            ],
            'citas' => $citas,
        ];
    }
}
