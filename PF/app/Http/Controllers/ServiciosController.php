<?php

namespace App\Http\Controllers;

use App\Servicio;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function getAll(){
        //Se obtienen todos los Servicios de la tabla
        $servicios = Servicio::all();
        return $servicios;
    }

    public function getServicio($id){
        
        $servicio = Servicio::findOrFail($id);

        return $servicio;
    }

    public function addServicio(Request $request){
        //////////// SE AGREGA UN NUEVO Servicio GUARDANDO LOS DATOS DEL REGISTRO
        
        $servicio = new Servicio();

        
        $servicio = $this->guardar($request, $servicio);

     
        $servicio->save();
    }

    public function updateServicio(Request $request){
         //////////// SE MODIFICA UN Servicio DEPENDIENDO DEL ID SUSTITUYENDO LOS DATOS DEL REGISTRO
    
        $servicio = Servicio::findOrFail($request->id);

       
        $servicio = $this->guardar($request, $servicio);

        
        $servicio->save();

       
        return $servicio;
    }

    public function deleteServicio($id){
       ////////// SE ELIMINA EL servicio DEPENDIENDO DEL ID
        $servicios = Servicio::destroy($id);
        return $servicios;
    }

    public function guardar($request, $servicio){
        //Se agregan los vlaores a las variables asignadas
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        return $servicio;
    }

}
