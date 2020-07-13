<?php

namespace App\Http\Controllers;

use App\Enfermedad;
use Illuminate\Http\Request;

class EnfermedadsController extends Controller
{
    public function getAll(){
        //Se obtienen todos los enfermedads de la tabla
        $enfermedads = Enfermedad::all();
        return $enfermedads;
    }

    public function getEnfermedad($id){
        
        $enfermedad = Enfermedad::findOrFail($id);

        return $enfermedad;
    }

    public function addEnfermedad(Request $request){
        //////////// SE AGREGA UN NUEVO enfermedad GUARDANDO LOS DATOS DEL REGISTRO
        
        $enfermedad = new Enfermedad();

        
        $enfermedad = $this->guardar($request, $enfermedad);

     
        $enfermedad->save();
    }

    public function updateEnfermedad(Request $request){
         //////////// SE MODIFICA UN enfermedad DEPENDIENDO DEL ID SUSTITUYENDO LOS DATOS DEL REGISTRO
    
        $enfermedad = Enfermedad::findOrFail($request->id);

       
        $enfermedad = $this->guardar($request, $enfermedad);

        
        $enfermedad->save();

       
        return $enfermedad;
    }

    public function deleteEnfermedad($id){
       ////////// SE ELIMINA EL enfermedad DEPENDIENDO DEL ID
        $enfermedads = Enfermedad::destroy($id);
        return $enfermedads;
    }

    public function guardar($request, $enfermedad){
        //Se agregan los vlaores a las variables asignadas
        $enfermedad->nombre = $request->nombre;
        $enfermedad->descripcion = $request->descripcion;
        return $enfermedad;
    }

}
