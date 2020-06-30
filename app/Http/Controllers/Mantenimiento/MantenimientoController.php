<?php

namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MantenimientoController extends Controller
{
    
/* -------------------------------------------------------------------------- */
/*                               OBTENER SECTOR                               */
/* -------------------------------------------------------------------------- */

    public function getSector()
    {      
      $res = DB::select( DB::raw("SELECT `id`, `sector_nombre`, `sector_abreviado`, `estado` FROM `sector`
       "));
          return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                              ACTUALIZAR SECTOR                             */
/* -------------------------------------------------------------------------- */

    public function updSector(Request $request, $id)
    {
 
      $res =  DB::table('sector')
      ->where('id', $id)
      ->update([  
        'sector_nombre' => $request->input('sector_nombre'),
        'sector_abreviado' => $request->input('sector_abreviado'),
        'estado' => $request->input('estado')]);
        
        return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                                CREAR SECTOR                                */
/* -------------------------------------------------------------------------- */

    public function setSector(Request $request)
    {      
     try {

        $id = DB::table('sector')->insertGetId([
            'sector_nombre' =>  $request->sector_nombre,  
            'sector_abreviado' => $request->sector_abreviado,        
            'estado' => 'ACTIVO'
        ]);    
                  
     } catch (\Throwable $th) {
         return  response()->json('NO SE PUDO CREAR EL TURNO ERROR :'. $th, "500");
     }
     return response()->json($id, "200");
    }


/* -------------------------------------------------------------------------- */
/*                           OBTENER SECTOR USUARIO                           */
/* -------------------------------------------------------------------------- */

    public function getSectorUsuario()
    {   
      $res = DB::select( DB::raw("SELECT sector.id , `sector_nombre`, `sector_abreviado`, `estado`,sector_usuario.id as sector_usuario_id, sector_usuario.usuario_id, sector_usuario.fecha_ingreso, sector_usuario.puesto_defecto, users.nombreyapellido 
      FROM `sector`, sector_usuario, users 
      WHERE  sector_usuario.sector_id = sector.id AND users.id = sector_usuario.usuario_id
       "));
          return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                          ACTUALIZAR SECTOR USUARIO                         */
/* -------------------------------------------------------------------------- */

    public function updSectorUsuario(Request $request, $id)
    {
        $tmp_fecha = str_replace('/', '-', $request->input('fecha_ingreso'));
        $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha)); 

      $res =  DB::table('sector_usuario')
      ->where('id', $id)
      ->update([  
        'usuario_id' => $request->input('usuario_id'),
        'sector_id' => $request->input('sector_id'),
        'puesto_defecto' => $request->input('puesto_defecto'),
        'fecha_ingreso' => $fecha_ingreso]);
        
        return response()->json($res, "200");
    }



/* -------------------------------------------------------------------------- */
/*                           INSERTAR SECTOR USUARIO                          */
/* -------------------------------------------------------------------------- */

    public function setSectorUsuario(Request $request)
    {      
     try {

        $id = DB::table('sector_usuario')->insertGetId([
            'usuario_id' =>  $request->usuario_id,  
            'sector_id' => $request->sector_id,        
            'puesto_defecto' => $request->puesto_defecto,   
            'fecha_ingreso' => date("Y-m-d H:i:s")
        ]);    
                  
     } catch (\Throwable $th) {
         return  response()->json('NO SE PUDO CREAR EL TURNO ERROR :'. $th, "500");
     }
     return response()->json($id, "200");
    }


/* -------------------------------------------------------------------------- */
/*                       OBTENER SECTOR USUARIO ASOCIADO                      */
/* -------------------------------------------------------------------------- */

    public function getSectorUsuarioAsociado()
    {      
      $res = DB::select( DB::raw(
     "SELECT sector.id , `sector_nombre`, `sector_abreviado`, `estado`,sector_usuario_asociado.id as sector_usuario_id,
     sector_usuario_asociado.sector_usuario_id, sector_usuario_asociado.regla_id,
      sector_usuario_asociado.fecha_creacion, users.nombreyapellido , reglas.regla, reglas.prioridad, sector_usuario.puesto_defecto
     FROM sector, sector_usuario_asociado, sector_usuario, users,  reglas 
     WHERE  sector_usuario_asociado.sector_id = sector.id AND sector_usuario.id = sector_usuario_asociado.sector_usuario_id AND reglas.id = sector_usuario_asociado.regla_id AND users.id = sector_usuario.usuario_id
       "));
          return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                     ACTUALIZAR SECTOR USUARIO ASOCIADO                     */
/* -------------------------------------------------------------------------- */

    public function updSectorUsuarioAsociado(Request $request, $id)
    {
        // EL USUARIO NO PUEDE TENER UN SECTOR PRINCIPAL Y UN SECTOR ASOCIADO IGUALES
      $res =  DB::table('secto_usuario_asociado')
      ->where('id', $id)
      ->update([  
        'sector_usuario_id' => $request->input('sector_usuario_id'),
        'sector_id' => $request->input('sector_id'),
        'fecha_ingreso' => date("Y-m-d H:i:s"),
        'usuario_modifica_id' => $request->input('usuario_modifica_id'),
        'regla_id' => $request->input('regla_id')]);
        
        return response()->json($res, "200");
    }


/* -------------------------------------------------------------------------- */
/*                      INSERTAR SECTOR USUARIO ASOCIADO                      */
/* -------------------------------------------------------------------------- */

    public function setSectorUsuarioAsociado(Request $request)
    {      
     try {

        $id = DB::table('secto_usuario_asociado')->insertGetId([
            'sector_usuario_id' =>  $request->sector_usuario_id,  
            'sector_id' => $request->sector_id,        
            'fecha_ingreso' => date("Y-m-d H:i:s"),
            'usuario_modifica_id' => $request->usuario_modifica_id,
            'regla_id' => $request->regla_id,
        ]);    
                  
     } catch (\Throwable $th) {
         return  response()->json('NO SE PUDO CREAR EL TURNO ERROR :'. $th, "500");
     }
     return response()->json($id, "200");
    }


/* -------------------------------------------------------------------------- */
/*                       BORRAR SECTOR USUARIO ASOCIADO                       */
/* -------------------------------------------------------------------------- */

    public function delSectorUsuarioAsociado(Request $request)
    {
      $id = $request->input('id');
    DB::table('secto_usuario_asociado')->where('id', '=', $id)->delete();
    return response()->json($id, "200");
    }


   
/* -------------------------------------------------------------------------- */
/*                       OBTENER REGLA                                        */
/* -------------------------------------------------------------------------- */

public function getRegla()
{      
  $res = DB::select( DB::raw("SELECT `id`, `regla`, `prioridad` FROM `reglas`
   "));
      return response()->json($res, "200");
}


/* -------------------------------------------------------------------------- */
/*                     ACTUALIZAR REGLA                                        */
/* -------------------------------------------------------------------------- */

public function updRegla(Request $request, $id)
{

  $res =  DB::table('reglas')
  ->where('id', $id)
  ->update([  
    'regla' => $request->input('regla'),
    'prioridad' => $request->input('prioridad')
   ]);
    
    return response()->json($res, "200");
}


/* -------------------------------------------------------------------------- */
/*                      INSERTAR REGLA                                        */
/* -------------------------------------------------------------------------- */

public function setRegla(Request $request)
{      
 try {

    $id = DB::table('reglas')->insertGetId([
        'regla' =>  $request->regla,  
        'prioridad' => $request->prioridad       
    ]);    
              
 } catch (\Throwable $th) {
     return  response()->json('NO SE PUDO CREAR EL TURNO ERROR :'. $th, "500");
 }
 return response()->json($id, "200");
}
 
}
