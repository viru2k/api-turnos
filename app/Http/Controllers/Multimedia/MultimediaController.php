<?php

namespace App\Http\Controllers\Multimedia;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class MultimediaController extends ApiController
{
    

        
public function getMultimedia(Request $request){

    $usuario_id = $request->input('usuario_id');

   $res = DB::select( DB::raw(
   " SELECT `id`, `archivo_nombre`, `archivo_nombre_original`, `archivo_descripcion`, `orden`, `fecha_carga`, `fecha_vencimiento`, `tiene_vencimiento` FROM `multimedia` ORDER BY `orden` ASC
  "));
     
     return $res;
}
}
