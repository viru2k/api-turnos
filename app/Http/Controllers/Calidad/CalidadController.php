<?php

namespace App\Http\Controllers\Calidad;
use Illuminate\Support\Facades\DB; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CalidadController extends ApiController
{
 

/* -------------------------------------------------------------------------- */
/*                      ENCABEZADO DE CONTROL DE CALIDAD                      */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlEncabezado(Request $request)
  {      
    $res = DB::select( DB::raw("SELECT `id`, `calidad_titulo`, `calidad_descripcion`, `ficha_nro` FROM `calidad_control`
    "));
        return response()->json($res, "200");
  }


  public function setCalidadControlEncabezado(Request $request)
  {
    $id =    DB::table('calidad_control')->insertGetId([
      
      'calidad_titulo' => $request->calidad_titulo, 
      'calidad_descripcion' => $request->calidad_descripcion,    
      'ficha_nro' => $request->ficha_nro
  ]);    
    return response()->json($id, "200");  
  }

  
  public function putCalidadControlEncabezado(Request $request, $id)
  {
    $res =  DB::table('calidad_control')
    ->where('id', $id)
    ->update([
      'calidad_titulo' => $request->input('calidad_titulo'),
      'calidad_descripcion' => $request->input('calidad_descripcion'),
      'ficha_nro' => $request->input('ficha_nro')
      ]);
      
      return response()->json($res, "200");
  }
  

/* -------------------------------------------------------------------------- */
/*                      PARAMETROS DEL CONTROL DE CALIDAD                     */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlParametros(Request $request)
  {      
    $res = DB::select( DB::raw("SELECT `id`, `parametro`, estado FROM `calidad_parametro`
    "));
        return response()->json($res, "200");
  }

  public function setCalidadControlParametros(Request $request)
  {
    $id =    DB::table('calidad_parametro')->insertGetId([
      'parametro' => $request->parametro,
      'estado' => $request->estado
  ]);    
    return response()->json($id, "200");  
  }


  public function putCalidadControlParametros(Request $request, $id)
  {
    $res =  DB::table('calidad_parametro')
    ->where('id', $id)
    ->update([
      'parametro' => $request->input('parametro'),
      'estado' => $request->input('estado')
      ]);
  
      return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*                   MEZCLA CONTROL DE CALIDAD Y PARAMETROS                   */
/* -------------------------------------------------------------------------- */

  public function getCalidadControlParametroControl(Request $request)
  {      
    $control_calidad_id =  $request->input('control_calidad_id');   

    $res = DB::select( DB::raw("SELECT calidad_control_parametro.id, calidad_control_parametro.parametro_id, calidad_control_parametro.control_calidad_id,
     calidad_control.calidad_titulo, calidad_control.calidad_descripcion, calidad_control.ficha_nro, calidad_parametro.parametro, calidad_parametro.estado ,
      calidad_control_parametro.parametro_maximo, calidad_control_parametro.parametro_minimo
    FROM calidad_control_parametro, calidad_parametro, calidad_control 
    WHERE calidad_control_parametro.parametro_id = calidad_parametro.id AND calidad_control_parametro.control_calidad_id = calidad_control.id AND calidad_control_parametro.control_calidad_id = :control_calidad_id
    "),
     array(                       
      'control_calidad_id' => $control_calidad_id
    )
    );
        return response()->json($res, "200");
  }


  public function setCalidadControlParametroControl(Request $request)
  {
    $id =    DB::table('calidad_control_parametro')->insertGetId([
      'parametro_id' => $request->parametro_id,
      'control_calidad_id' => $request->control_calidad_id,
      'parametro_maximo' => $request->parametro_maximo,
      'parametro_minimo' => $request->parametro_minimo
  ]);    
    return response()->json($id, "200");  
  }


  public function putCalidadControlParametroControl(Request $request, $id)
  {
    $res =  DB::table('calidad_control_parametro')
    ->where('id', $id)
    ->update([
      'parametro_id' => $request->input('parametro_id'),
      'control_calidad_id' => $request->input('control_calidad_id'),
      'parametro_maximo' => $request->input('parametro_maximo'),
      'parametro_minimo' => $request->input('parametro_minimo')
      ]);
  
      return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*             ALMACENO EL CONTRO REALIZADO UN PROCESO PRODUCTIVO             */
/* -------------------------------------------------------------------------- */

  public function setCalidadControlParametroControlValor(Request $request)
  {
    $i = 0;
    $r = $request;
try { 

$cont = count($request->all());
//var_dump($request);
  while($i< $cont) {
   // var_dump($res[$i]['parametro_id']);
  // var_dump($req[$i]);
 
   // echo $req[$i]['id'];
     $id =    DB::table('calidad_control_parametro_valor')->insertGetId([
      'calidad_control_parametro_id' => $r[$i]['id'],
      'calidad_valor' => $r[$i]['calidad_valor'],
      'usuario_modifica_id' => $r[$i]['usuario_modifica_id'],
      'es_no_conformidad' => $r[$i]['no_conformidad'],
      'tiene_accion_correctiva' => $r[$i]['es_accion_correctiva'],
      'es_no_conformidad_descripcion' => $r[$i]['es_no_conformidad_descripcion'],
      'tiene_accion_correctiva_descripcion' => $r[$i]['tiene_accion_correctiva_descripcion'],
      'fecha_carga' => $r[$i]['fecha'],
      'produccion_proceso_id' => $r[$i]['produccion_proceso_id'], 
      'tiene_desviacion_parametro' => $r[$i]['tiene_desviacion_parametro']
  ]);    
      $i++;
  }
} catch (\Throwable $th) {
  return response()->json($th, "500");  
}

  
   return response()->json($id, "200");  
  }


/* -------------------------------------------------------------------------- */
/*                    DETALLE DE CONTROL POR ID DE PROCESO                    */
/* -------------------------------------------------------------------------- */

    public function getControlByProcesoId(Request $request)
    {
        $produccion_proceso_id =  $request->input('produccion_proceso_id');         
      $res = DB::select( DB::raw("SELECT calidad_control.id, calidad_control_parametro_id, calidad_valor, calidad_control_parametro_valor.usuario_modifica_id, es_no_conformidad, tiene_accion_correctiva, 
      fecha_carga, produccion_proceso_id, calidad_control.calidad_titulo, calidad_control.calidad_descripcion,  
      calidad_control.ficha_nro, calidad_parametro.parametro, users.nombreyapellido, produccion_proceso.lote, orden_produccion_detalle.fecha_produccion, 
      articulo.nombre as articulo_nombre ,calidad_control_parametro.parametro_maximo, calidad_control_parametro.parametro_minimo
      FROM  calidad_control, calidad_control_parametro, calidad_parametro , calidad_control_parametro_valor, produccion_proceso, orden_produccion_detalle, articulo, users 
      WHERE calidad_control.id = calidad_control_parametro.control_calidad_id AND calidad_parametro.id = calidad_control_parametro.parametro_id 
      AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id AND calidad_control_parametro_valor.usuario_modifica_id = users.id 
      AND calidad_control_parametro_valor.produccion_proceso_id = produccion_proceso.id AND calidad_parametro.estado = 'ACTIVO'
      AND produccion_proceso.articulo_id = articulo.id AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id AND produccion_proceso.id = :produccion_proceso_id
      "), array(                       
            'produccion_proceso_id' => $produccion_proceso_id
          ));

          return response()->json($res, "200");
    }

    
    public function getControlByProcesoByDates(Request $request)
    {
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
      $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
      $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));   

      $res = DB::select( DB::raw("SELECT calidad_control.id, calidad_control_parametro_id, calidad_valor, calidad_control_parametro_valor.usuario_modifica_id, es_no_conformidad, tiene_accion_correctiva, 
      fecha_carga, produccion_proceso_id, calidad_control.calidad_titulo, calidad_control.calidad_descripcion,  
      calidad_control.ficha_nro, calidad_parametro.parametro, users.nombreyapellido, produccion_proceso.lote, orden_produccion_detalle.fecha_produccion, 
      articulo.nombre as articulo_nombre , calidad_control_parametro.parametro_maximo, calidad_control_parametro.parametro_minimo, tiene_desviacion_parametro
      FROM  calidad_control, calidad_control_parametro, calidad_parametro , calidad_control_parametro_valor, produccion_proceso, orden_produccion_detalle, articulo, users 
      WHERE calidad_control.id = calidad_control_parametro.control_calidad_id AND calidad_parametro.id = calidad_control_parametro.parametro_id 
      AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id AND calidad_control_parametro_valor.usuario_modifica_id = users.id 
      AND calidad_control_parametro_valor.produccion_proceso_id = produccion_proceso.id AND calidad_parametro.estado = 'ACTIVO'
      AND produccion_proceso.articulo_id = articulo.id AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id AND orden_produccion_detalle.fecha_produccion 
      BETWEEN   :fecha_desde AND :fecha_hasta
      "), array(                       
            'fecha_desde' => $fecha_desde,
            'fecha_desde' => $fecha_hasta,
          ));

          return response()->json($res, "200");
    }



      
    public function getDesviacionesParametroCalidadByProcesoByDates(Request $request)
    {
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
      $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha));   
      $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
      $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha));   
      
      $res = DB::select( DB::raw("SELECT orden_produccion.id as orden_produccion_id, produccion_proceso.id as produccion_proceso_id, produccion_proceso.hora_inicio, produccion_proceso.hora_fin, 
      produccion_proceso.estado, produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_pendiente, produccion_proceso.cantidad_producida,
      calidad_control_parametro_valor.calidad_control_parametro_id,
      SUM(calidad_control_parametro_valor.tiene_desviacion_parametro) AS parametro_desviacion, articulo.nombre, articulo.descripcion, maquina.maquina_nombre,
      produccion_proceso.hora_inicio, produccion_proceso.hora_fin,  produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_pendiente, produccion_proceso.cantidad_producida, produccion_proceso.lote
      FROM orden_produccion,orden_produccion_detalle, calidad_control_parametro_valor, produccion_proceso, articulo, maquina  
      WHERE orden_produccion.id = orden_produccion_detalle.orden_produccion_id AND  calidad_control_parametro_valor.produccion_proceso_id = produccion_proceso.id 
      AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id 
      AND orden_produccion_detalle.id = produccion_proceso.orden_produccion_detalle_id 
      AND orden_produccion_detalle.articulo_id = articulo.id 
      AND produccion_proceso.maquina_id = maquina.id  AND produccion_proceso.hora_inicio 
      BETWEEN   '".$fecha_desde."' AND  '".$fecha_hasta."'
      GROUP by produccion_proceso.id 
      "));

          return response()->json($res, "200");
    }


    public function getControlesDetalleByIdProduccion(Request $request)
    {
      $produccion_proceso_id = $request->input('produccion_proceso_id');

      
      $res = DB::select( DB::raw("SELECT produccion_proceso.id, produccion_proceso.lote, produccion_proceso.hora_fin, produccion_proceso.hora_inicio, 
      produccion_proceso.estado, calidad_control_parametro_valor.id as  calidad_control_parametro_valor_id , calidad_control_parametro_valor.calidad_valor, calidad_control_parametro_valor.es_no_conformidad, 
      calidad_control_parametro_valor.tiene_accion_correctiva,calidad_control_parametro_valor.tiene_accion_correctiva_descripcion, calidad_control_parametro_valor.es_no_conformidad_descripcion, calidad_control_parametro_valor.fecha_carga,   calidad_control.calidad_titulo, calidad_control.calidad_descripcion, 
      calidad_control.ficha_nro, calidad_parametro.parametro , calidad_parametro.id as calidad_parametro_id  
      FROM produccion_proceso, calidad_control_parametro_valor, calidad_control_parametro, calidad_control, calidad_parametro 
      WHERE produccion_proceso.id = calidad_control_parametro_valor.produccion_proceso_id AND calidad_control_parametro.parametro_id = calidad_parametro.id 
      AND calidad_control_parametro.control_calidad_id = calidad_control.id 
      AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id AND  produccion_proceso.id = :produccion_proceso_id
      "), array(                       
            'produccion_proceso_id' => $produccion_proceso_id
          ));

          return response()->json($res, "200");
    }


    public function getControlesByIdProduccion(Request $request)
    {
      $produccion_proceso_id = $request->input('produccion_proceso_id');

      
      $res = DB::select( DB::raw("SELECT produccion_proceso.id, produccion_proceso.lote, produccion_proceso.hora_fin, produccion_proceso.hora_inicio, produccion_proceso.estado, calidad_control_parametro_valor.id as  calidad_control_parametro_valor_id , calidad_control_parametro_valor.calidad_valor, calidad_control_parametro_valor.es_no_conformidad, calidad_control_parametro_valor.tiene_accion_correctiva, calidad_control_parametro_valor.es_no_conformidad_descripcion, calidad_control.calidad_titulo, calidad_control.calidad_descripcion, calidad_control.ficha_nro, calidad_parametro.parametro , calidad_parametro.id as calidad_parametro_id  
      FROM produccion_proceso, calidad_control_parametro_valor, calidad_control_parametro, calidad_control, calidad_parametro 
      WHERE produccion_proceso.id = calidad_control_parametro_valor.produccion_proceso_id 
      AND calidad_control_parametro.parametro_id = calidad_parametro.id 
      AND calidad_control_parametro.control_calidad_id = calidad_control.id AND calidad_control_parametro_valor.calidad_control_parametro_id = calidad_control_parametro.id   AND 
      produccion_proceso.id = :produccion_proceso_id
      GROUP BY calidad_control.id 
      "), array(                       
            'produccion_proceso_id' => $produccion_proceso_id
          ));

          return response()->json($res, "200");
    }

    

    public function delControlParametro(Request $request)
    {
      $id = $request->input('id');
    DB::table('calidad_control_parametro')->where('id', '=', $id)->delete();
    return response()->json($id, "200");
    }
}
