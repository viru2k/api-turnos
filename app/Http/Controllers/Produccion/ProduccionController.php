<?php

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Articulo; 
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProduccionController extends ApiController
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Articulo = Articulo::all();
        return $this->showAll($Articulo);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $Articulo)
    {
        return $this->showOne($Articulo);
    }

/* -------------------------------------------------------------------------- */
/*                             CREO LA PRODUCCION                             */
/* -------------------------------------------------------------------------- */
/* 
    public function setProduccion(Request $request)
    {
        $tmp_fecha = str_replace('/', '-', $request->fecha_produccion);
        $fecha_produccion =  date('Y-m-d H:i', strtotime($tmp_fecha));   
     

      $produccion_id =    DB::table('produccion')->insertGetId([
        
        'orden_produccion_articulos_id' => $request->orden_produccion_articulos_id,        
        'articulo_id' => $request->articulo_id,         
        'fecha_produccion' => $fecha_produccion, 
        'unidad_id' => $request->unidad_id,        
        'cantidad_botella' => $request->cantidad_botella, 
        'cantidad_litros' => $request->cantidad_litros, 
        'sector_id' => $request->sector_id, 
        'usuario_alta_id' => $request->usuario_alta_id, 
        'usuario_modifica_id' => $request->usuario_alta_id, 
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    


    $stock_id =    DB::table('produccion_stock')->insertGetId([
        
      'produccion_id' => $produccion_id, 
      'fecha_ingreso' => $fecha_produccion, 
      'cantidad_original' => $fecha_produccion,  
      'cantidad_original' => $request->cantidad_botella, 
      'cantidad_salida' => '0', 
      'existencia' => $request->cantidad_botella, 
      'usuario_alta_id' => $request->usuario_alta_id, 
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s")
  ]);    

      return response()->json($stock_id, "200");  
    } */


/* -------------------------------------------------------------------------- */
/*                           ACTUALIZO LA PRODUCCION                          */
/* -------------------------------------------------------------------------- */

    public function update(Request $request, $id)
    {

        $tmp_fecha = str_replace('/', '-', $request->input('fecha_produccion'));
        $fecha_produccion =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      
      $res =  DB::table('produccion')
      ->where('id', $id)
      ->update([
        'fecha_produccion' => $fecha_produccion,
        'descripcion' => $request->input('es_habilitado'),
        'unidad_id' => $request->input('unidad_id'),
        'cantidad_botella' => $request->input('cantidad_botella'),
        'cantidad_litros' => $request->input('cantidad_litros'),
        'sector_id' => $request->input('sector_id'),
        'usuario_alta_id' => $request->input('usuario_alta'),
        'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*                        INGRESO LA PRODUCCION DE STOCK                       */
/* -------------------------------------------------------------------------- */

    public function setProduccionStock(Request $request)
    {
        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
        $tmp_fecha = str_replace('/', '-', $request->fecha_egreso);
        $fecha_egreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
      $id =    DB::table('produccion_stock')->insertGetId([
        
        'produccion_id' => $request->produccion_id, 
        'fecha_egreso' => $fecha_egreso, 
        'fecha_ingreso' => $fecha_ingreso, 
        'fecha_produccion' => $fecha_produccion, 
        'unidad_id' => $request->unidad_id,        
        'cantidad_original' => $request->cantidad_original, 
        'cantidad_salida' => $request->cantidad_salida, 
        'existencia' => $request->existencia, 
        'sector_id' => $request->sector_id, 
        'usuario_alta_id' => $request->usuario_alta_id, 
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);    


      return response()->json($turno, "200");  
    }

/* -------------------------------------------------------------------------- */
/*                           ACTUALIZO LA PRODUCCION                          */
/* -------------------------------------------------------------------------- */

//TODO  CALCULAR LA EXISTENCIA BASANDOSE EN LA CANTIDAD DE SALIDA
    public function updProduccionStock(Request $request, $id)
    {

        $tmp_fecha = str_replace('/', '-', $request->fecha_ingreso);
        $fecha_ingreso =  date('Y-m-d H:i', strtotime($tmp_fecha));   
        $tmp_fecha = str_replace('/', '-', $request->fecha_egreso);
        $fecha_egreso =  date('Y-m-d H:i', strtotime($tmp_fecha));
      
      $res =  DB::table('produccion_stock')
      ->where('id', $id)
      ->update([
        'fecha_ingreso' => $fecha_ingreso,
        'fecha_egreso' => $fecha_egreso,
        'cantidad_original' => $request->input('cantidad_original'),
        'cantidad_salida' => $request->input('cantidad_salida'),
        'existencia' => $request->input('existencia'),
        'usuario_alta_id' => $request->input('usuario_alta_id'),
        'updated_at' => date("Y-m-d H:i:s")]);

        return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*    OBTENGO EL STOCK DE PRODUCCION SIEMPRE QUE LA EXISTENCIA SE MAYOR A 0   */
/* -------------------------------------------------------------------------- */

    public function getStockProduccion(Request $request)
    {
    
        //$produccion_id =  $request->input('produccion_id');  
     
    
      $res = DB::select( DB::raw("SELECT articulo.descripcion as articulo_descripcion,  botellas, pisos, pack, articulo.litros, produccion.orden_produccion, produccion.id as produccion_id, produccion_stock.id as produccion_stock_id, produccion.fecha_produccion, produccion.fecha_produccion, produccion.cantidad_botella, produccion.cantidad_litros, unidad.descripcion as unidad_descripcion, users.nombreyapellido, sector.nombre AS sector_nombre, produccion_stock.fecha_ingreso, produccion_stock.fecha_egreso, produccion_stock.cantidad_original, produccion_stock.cantidad_salida, produccion_stock.existencia , produccion_movimiento.fecha_movimiento, produccion_movimiento.cantidad_salida as produccion_movimiento_cantidad_salida
      FROM `produccion_stock`,produccion_movimiento,  produccion, unidad, users, sector, articulo 
      WHERE  produccion.id = produccion_stock.produccion_id AND produccion.unidad_id = unidad.id AND produccion_movimiento.produccion_stock_id = produccion_stock.id AND produccion_stock.usuario_alta_id = users.id AND sector.id = produccion.sector_id AND produccion.articulo_id = articulo.id AND produccion_stock.existencia > 0
      
       "));
    
    return response()->json($res, "200");
    }

/* -------------------------------------------------------------------------- */
/*            OBTENGO LA PRODUCCION REALIZADA POR PERIODO DE FECHA            */
/* -------------------------------------------------------------------------- */

//TODO  FALTA REALIZAR LA CONSULTA

public function getProduccionStockByDates(Request $request)
{

  $tmp_fecha = str_replace('/', '-', $request->input('fecha_desde'));
  $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
  $tmp_fecha = str_replace('/', '-', $request->input('fecha_hasta'));
  $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));  
 

 
  $res = DB::select( DB::raw("SELECT articulo.descripcion as articulo_descripcion,  botellas, pisos, pack, articulo.litros, produccion.orden_produccion, produccion.id as produccion_id, produccion_stock.id as produccion_stock_id, produccion.fecha_produccion, produccion.fecha_produccion, produccion.cantidad_botella, produccion.cantidad_litros, unidad.descripcion as unidad_descripcion, users.nombreyapellido, sector.nombre AS sector_nombre, produccion_stock.fecha_ingreso, produccion_stock.fecha_egreso, produccion_stock.cantidad_original, produccion_stock.cantidad_salida, produccion_stock.existencia , produccion_movimiento.fecha_movimiento, produccion_movimiento.cantidad_salida as produccion_movimiento_cantidad_salida
  FROM `produccion_stock`,produccion_movimiento,  produccion, unidad, users, sector, articulo 
  WHERE  produccion.id = produccion_stock.produccion_id AND produccion.unidad_id = unidad.id AND produccion_movimiento.produccion_stock_id = produccion_stock.id AND produccion_stock.usuario_alta_id = users.id AND sector.id = produccion.sector_id AND produccion.articulo_id = articulo.id AND produccion.fecha_produccion BETWEEN   :fecha_desde  and :fecha_hasta
  
   "), array(                       
        'fecha_desde' => $fecha_desde,
        'fecha_hasta' => $fecha_hasta
      ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*          CREO LA ORDEN DE produccion Y AGREGO EL LISTADO DE PRODUCTOS          */
/* -------------------------------------------------------------------------- */

public function setOrdenProduccion(Request $request)
{
    $tmp_fecha = str_replace('/', '-', $request->fecha);
    $fecha =  date('Y-m-d H:i', strtotime($tmp_fecha));  

  $id =    DB::table('orden_produccion')->insertGetId([
    
    'fecha_produccion' => $request->fecha_produccion, 
    'usuario_id' => $request->usuario_id,  
    'estado' => 'ACTIVO',  
    'created_at' => date("Y-m-d H:i:s"),
    'updated_at' => date("Y-m-d H:i:s")
]);    

  $t = $request->articulo;

  foreach ($t as $res) {
    if($res["cantidad"] != 0){
      DB::table('orden_produccion_articulos')->insertGetId([            
        'orden_produccion_id' => $id,
        'articulo_id' => $res["id"],          
        'cantidad' => $res["cantidad"],       
        'usuario_id' => $request->usuario_id,         
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")    
    ]);      
    }       
  }
 return response()->json($id, "200");  
}


/* -------------------------------------------------------------------------- */
/*                  OBTENGO LAS ORDENES DE PRODUCCION INCOMPLETAS                 */
/* -------------------------------------------------------------------------- */

public function getOrdenProduccionEstado(Request $request)
  {
    $estado = $request->input('estado');
    $res = DB::select( DB::raw("SELECT orden_produccion.id, orden_produccion.fecha_creacion, orden_produccion.descripcion, orden_produccion.observacion,  orden_produccion.estado, orden_produccion.fecha_desde, orden_produccion.fecha_hasta, users.id as usuario_id, users.nombreyapellido   
    FROM orden_produccion,users WHERE orden_produccion.usuario_modifica_id = users.id  ORDER BY fecha_creacion DESC
    
    "), array(                       
          'estado' => $estado
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*              OBTENGO LA PRODUCCION ASOCIADA A ORDEN DE PRODUCCION              */
/* -------------------------------------------------------------------------- */

public function  getOrdenProduccionDetalleByEstado(Request $request)
  {
    $estado = $request->input('estado');
    $res = DB::select( DB::raw("SELECT articulo.id as articulo_id,  botellas, pisos, pack, articulo.litros, orden_produccion.id, fecha_produccion, orden_produccion_articulos.id as orden_produccion_articulos_id, orden_produccion_articulos.cantidad, articulo.descripcion , users.nombreyapellido, sector.id as sector_id, sector.nombre as sector_nombre 
    FROM `orden_produccion`, orden_produccion_articulos, articulo, users, sector 
    WHERE orden_produccion_articulos.orden_produccion_id = orden_produccion.id AND orden_produccion_articulos.articulo_id = articulo.id AND orden_produccion.usuario_id = users.id AND orden_produccion.sector_id = sector.id AND  orden_produccion.estado = :estado ORDER BY fecha_produccion ASC
    
    "), array(                       
          'estado' => $estado          
        ));

        return response()->json($res, "200");
  }

  public function getOrdenProduccionDetalleById(Request $request)
  {
    $id = $request->input('id');
    $res = DB::select( DB::raw("SELECT orden_produccion.id, fecha_produccion, orden_produccion_articulos.id as orden_produccion_articulos_id, orden_produccion_articulos.cantidad, articulo.descripcion , users.nombreyapellido , botellas, pisos, pack, articulo.litros , sector.id as sector_id, sector.nombre as sector_nombre
    FROM `orden_produccion`, orden_produccion_articulos, articulo, users , sector
    WHERE orden_produccion_articulos.orden_produccion_id = orden_produccion.id AND orden_produccion_articulos.articulo_id = articulo.id  AND orden_produccion.sector_id = sector.id AND orden_produccion.usuario_id = users.id AND  orden_produccion.id = :id
    
    "), array(                       
          'id' => $id          
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*     OBTENGO EL LISTADO DE PRODUCCION GENERADA PARA UNA ORDEN DE produccion     */
/* -------------------------------------------------------------------------- */

  public function getProduccionByOrdenProduccion(Request $request)
  {
    $id = $request->input('id');
    $articulo_id = $request->input('articulo_id');

    $res = DB::select( DB::raw("SELECT articulo.id as articulo_id,  botellas, pisos, pack, articulo.litros, orden_produccion.id, orden_produccion_articulos.id as orden_produccion_articulos_id, orden_produccion_articulos.cantidad, articulo.descripcion , users.nombreyapellido, produccion.id as produccion_id, 
    produccion.fecha_produccion, produccion.cantidad_botella, produccion.cantidad_litros, produccion_stock.fecha_egreso, produccion_stock.fecha_ingreso, produccion_stock.cantidad_original, 
    produccion_stock.cantidad_salida, produccion_stock.existencia, produccion_stock.id as produccion_stock_id, sector.id as sector_id, sector.nombre as sector_nombre 
    FROM `orden_produccion`, orden_produccion_articulos, articulo,produccion_stock,produccion, users , sector
    WHERE orden_produccion_articulos.orden_produccion_id = orden_produccion.id AND orden_produccion_articulos.articulo_id = articulo.id AND produccion.orden_produccion_articulos_id = orden_produccion_articulos.id  
    AND  produccion_stock.produccion_id = produccion.id AND produccion_stock.usuario_alta_id = users.id  AND orden_produccion.sector_id = sector.id
    AND orden_produccion.id  = :id AND  produccion.articulo_id  = :articulo_id ORDER BY fecha_produccion DESC
    
    "), array(                       
          'id' => $id,
          'articulo_id' => $articulo_id
        ));

        return response()->json($res, "200");
  }

  public function getProduccionByOrdenProduccionTodos(Request $request)
  {
    $id = $request->input('id');
    $res = DB::select( DB::raw("SELECT articulo.id as articulo_id, botellas, pisos, pack, articulo.litros, orden_produccion.id, orden_produccion_articulos.id as orden_produccion_articulos_id, orden_produccion_articulos.cantidad, articulo.descripcion ,
     users.nombreyapellido, produccion.id as produccion_id, produccion.fecha_produccion, produccion.cantidad_botella, produccion.cantidad_litros, produccion_stock.fecha_egreso, produccion_stock.fecha_ingreso, produccion_stock.cantidad_original,
     produccion_stock.cantidad_salida, produccion_stock.existencia, produccion_stock.id as produccion_stock_id, sector.id as sector_id, sector.nombre as sector_nombre 
    FROM `orden_produccion`, orden_produccion_articulos, articulo,produccion_stock,produccion, users ,sector
    WHERE orden_produccion_articulos.orden_produccion_id = orden_produccion.id AND orden_produccion_articulos.articulo_id = articulo.id AND produccion.orden_produccion_articulos_id = orden_produccion_articulos.id 
    AND  produccion_stock.produccion_id = produccion.id AND orden_produccion.sector_id = sector.id
    AND produccion_stock.usuario_alta_id = users.id  AND orden_produccion.id  = :id ORDER BY fecha_produccion DESC
    
    "), array(                       
          'id' => $id          
        ));

        return response()->json($res, "200");
  }

/* -------------------------------------------------------------------------- */
/*                    OBTENGO LOS INSUMOS PARA UN ARTICULO                    */
/* -------------------------------------------------------------------------- */


public function getInsumosByArticuloId(Request $request)
{
  $articulo_id = $request->input('articulo_id');
  $res = DB::select( DB::raw("SELECT articulo.descripcion as articulo_descripcion , botellas, pisos, pack, articulo.litros, articulo_confeccion.id,  articulo_confeccion.articulo_id, articulo_confeccion.cantidad as  articulo_confeccion_cantidad , insumo_stock.insumo_id, insumo_stock.cantidad, insumo_stock.cantidad_usada, insumo_stock.cantidad_existente, insumo_stock.fecha_ingreso, insumo_stock.fecha_finalizado, articulo_confeccion.cantidad as articulo_confeccion_cantidad,   insumo.descripcion as insumo_descripcion 
  FROM `articulo_confeccion`, articulo, insumo, insumo_stock 
  WHERE articulo_confeccion.articulo_id = articulo.id AND insumo.id = articulo_confeccion.insumo_id AND insumo.id = insumo_stock.insumo_id  AND articulo.id    = :articulo_id
  
  "), array(                       
        'articulo_id' => $articulo_id          
      ));

      return response()->json($res, "200");
}
  
/* -------------------------------------------------------------------------- */
/*                        OBTENCION DE LA TABLA SECTOR                        */
/* -------------------------------------------------------------------------- */

public function getSectorProduccion()
{  
  $res = DB::select( DB::raw("SELECT `id`, `nombre`, `empleados_produccion` FROM `sector`
  "));

      return response()->json($res, "200");
}



/* -------------------------------------------------------------------------- */
/*                   ACTUALIZAR EL ESTADO DE ORDEN DE produccion              */
/* -------------------------------------------------------------------------- */

public function updOrdenProduccion(Request $request)
{
  
  $res =  DB::table('orden_produccion')
  ->where('id', $request->input('id'))
  ->update([
    'usuario_modifica_id' => $request->input('usuario_modifica_id'),   
    'estado' => $request->input('estado'),       
    'updated_at' => date("Y-m-d H:i:s")]);

    return response()->json($res, "200");
}



/* ------------------------ ACTUALIZADO 20 -04- 2020 ------------------------ */


/* -------------------------------------------------------------------------- */
/*                             CREO LA PRODUCCION                             */
/* -------------------------------------------------------------------------- */


public function setProduccion(Request $request){

  $tmp_fecha = str_replace('/', '-', $request->fecha_creacion);
  $fecha_creacion =  date('Y-m-d', strtotime($tmp_fecha));  

  $tmp_fecha = str_replace('/', '-', $request->fecha_desde);
  $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));  

  $tmp_fecha = str_replace('/', '-', $request->fecha_hasta);
  $fecha_hasta =  date('Y-m-d H:i', strtotime($tmp_fecha));  
  

  $id =    DB::table('orden_produccion')->insertGetId([    
    'fecha_creacion' => $fecha_creacion,        
    'usuario_modifica_id' => $request->usuario_modifica_id , 
    'descripcion' => $request->descripcion,         
    'observacion' => $request->observacion,   
    'estado' => 'ACTIVO',   
    'fecha_desde' => $fecha_desde,  
    'fecha_hasta' => $fecha_hasta
]);    
  
// guardo el request en una variable para iterar
$t = $request->OrdenProduccionDetalle;

foreach ($t as $res) {
  $tmp_hora = str_replace('/', '-',  $res["horas"]);
  $hora =  date('H:i:s', strtotime($tmp_hora));  
  $tmp_fecha = str_replace('/', '-',  $res["fecha_produccion"]);
  $fecha_produccion =  date('Y-m-d H:i', strtotime($tmp_fecha));

  if($res["cantidad"] != 0){
    DB::table('orden_produccion_detalle')->insertGetId([            
      'orden_produccion_id' => $id,
      'articulo_id' => $res["id"],      
      'fecha_produccion' => $fecha_produccion,   
      'hora' => $hora,  
      'cantidad_solicitada' => $res["cantidad_solicitada"],       
      
      'cantidad_usada' =>  0,  
      'cantidad_existente' => $res["cantidad_solicitada"],  
      'grupo_id' => $res["grupo_id"],  
      'usuario_modifica_id' => $request->usuario_modifica_id,  
      'estado'        => 'ACTIVO',
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s")    
  ]);      
  }       
}
return response()->json($id, "200");  
}


public function updProduccionEstado(Request $request, $id){
  $res =  DB::table('orden_produccion')
  ->where('id', $id)
  ->update([      
    'estado' => $request->input('estado')]);

    return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                 OBTENGO EL LISTADO DE INSUMOS POR ARTICULO                 */
/* -------------------------------------------------------------------------- */

public function produccionArmadoDeProductoById(Request $request)
{
    $articulo_id = $request->input('articulo_id');
 
  $res = DB::select( DB::raw("SELECT stock_armado_producto.id AS stock_armado_producto_id, articulo.id ,articulo.nombre, articulo.descripcion, articulo_propiedades.unidades, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.volumen, unidad.descripcion as unidad_descripcion, users.nombreyapellido, stock_armado_producto.cantidad ,stock_armado_producto.insumo_id, insumo.nombre as insumo_nombre, insumo.descripcion as insumo_descripcion , stock_armado_producto.estado AS stock_armado_producto_estado
  FROM articulo_propiedades, articulo, unidad, users, stock_armado_producto, insumo 
  WHERE articulo_propiedades.articulo_id = articulo.id AND articulo.unidad_id = unidad.id AND articulo.usuario_modifica_id = users.id AND stock_armado_producto.articulo_id = articulo.id AND stock_armado_producto.insumo_id = insumo.id AND articulo.id  = :articulo_id
   "), array(                       
    'articulo_id' => $articulo_id
  ));

      return response()->json($res, "200");
}



/* -------------------------------------------------------------------------- */
/*                INSERTO UN INSUMO A LA CONFECCION DE ARTICULO               */
/* -------------------------------------------------------------------------- */

public function setStockArmadoProducto(Request $request){

  $id =    DB::table('stock_armado_producto')->insertGetId([
    'articulo_id' => $request->articulo_id, 
    'insumo_id' => $request->insumo_id,        
    'cantidad' => $request->cantidad, 
    'usuario_modifica_id' => $request->usuario_modifica_id,         
    'estado' => 'ACTIVO',   
    'created_at' => date("Y-m-d H:i:s"),
    'updated_at' => date("Y-m-d H:i:s")
]);    
  return response()->json($id, "200");  
}

/* -------------------------------------------------------------------------- */
/*                       ACTUALIZO EL INSUMO EN EL STOCK                      */
/* -------------------------------------------------------------------------- */

public function updateStockArmadoProducto(Request $request, $id){
  $res =  DB::table('stock_armado_producto')
  ->where('insumo_id', $request->input('insumo_id'))
  ->where('articulo_id', $request->input('articulo_id'))
  ->update([
    'articulo_id' => $request->input('articulo_id'),
    'insumo_id' => $request->input('insumo_id'),
    'cantidad' => $request->input('cantidad'),
    'usuario_modifica_id' => $request->input('usuario_modifica_id'),           
    'estado' => $request->input('estado'),  
    'updated_at' => date("Y-m-d H:i:s")]);

    return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                            BORRAR UNA ASOCIACION                           */
/* -------------------------------------------------------------------------- */

public function delStockArmadoProducto(Request $request){
  $id = $request->input('id');
  $res =  DB::table('stock_armado_producto')->delete($id);
  return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*        OBTENGO EL LISTADO DE PRODUCTOS DE UNA PRODUCCION PLANIFICADA       */
/* -------------------------------------------------------------------------- */

public function produccionDetalleByProduccionId(Request $request)
{
    $produccion_id = $request->input('produccion_id');
 
  $res = DB::select( DB::raw("SELECT orden_produccion.id, fecha_creacion, orden_produccion_detalle.usuario_modifica_id, orden_produccion.descripcion, orden_produccion.observacion, orden_produccion_detalle.estado, `fecha_desde`, `fecha_hasta`, 
  orden_produccion_detalle.fecha_produccion, orden_produccion_detalle.id as orden_produccion_detalle_id ,orden_produccion_detalle.cantidad_solicitada, orden_produccion_detalle.cantidad_usada, orden_produccion_detalle.cantidad_existente, orden_produccion_detalle.estado AS orden_produccion_detall_estado ,
  articulo.id as articulo_id, articulo.nombre, articulo.descripcion, articulo_propiedades.unidades, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.volumen 
  FROM orden_produccion, orden_produccion_detalle, articulo, articulo_propiedades 
  WHERE orden_produccion.id = orden_produccion_detalle.orden_produccion_id AND orden_produccion_detalle.articulo_id = articulo.id AND articulo.id = articulo_propiedades.articulo_id and orden_produccion.id = :produccion_id
   "), array(                       
    'produccion_id' => $produccion_id
  ));

      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*                            PROCESO DE PRODUCCION                           */
/* -------------------------------------------------------------------------- */


public function getProduccionProcesoByOrdenProduccionDetalleId(Request $request)
{
    $orden_produccion_detalle_id = $request->input('orden_produccion_detalle_id');
 
    try {
      $res = DB::select( DB::raw("SELECT produccion_proceso.id, `orden_produccion_detalle_id`, produccion_proceso.articulo_id, produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_pendiente, `cantidad_producida`, produccion_proceso.usuario_modifica_id, `maquina_id`, `hora_fin`, `hora_inicio`, produccion_proceso.estado, orden_produccion_detalle.id as orden_produccion_detalle_id, orden_produccion_detalle.fecha_produccion, orden_produccion_detalle.cantidad_solicitada as  orden_produccion_detalle_cantidad_solicitada, orden_produccion_detalle.cantidad_usada AS orden_produccion_detalle_cantidad_usada, orden_produccion_detalle.cantidad_existente AS orden_produccion_detalle_cantidad_existente, articulo.nombre, maquina.maquina_nombre  
  FROM `produccion_proceso`, orden_produccion_detalle, orden_produccion, articulo, maquina 
  WHERE  produccion_proceso.orden_produccion_detalle_id = orden_produccion_detalle.id AND orden_produccion_detalle.orden_produccion_id = orden_produccion.id AND produccion_proceso.articulo_id = articulo.id AND maquina.id = produccion_proceso.maquina_id  AND orden_produccion_detalle_id = :orden_produccion_detalle_id
   "), array(                       
    'orden_produccion_detalle_id' => $orden_produccion_detalle_id
  ));
    } catch (\Throwable $th) {
      return response()->json('ERROR INTERNO DEL SERVIDOR '.$th, "500");
    }


      return response()->json($res, "200");
}


public function setProduccionProceso(Request $request){

  $tmp_fecha = str_replace('/', '-',  $request->hora_inicio);
  $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

  
    $tmp_fecha = str_replace('/', '-',  $request->fecha_hasta);
    $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

    try {
      

      $id =    DB::table('produccion_proceso')->insertGetId([
        'orden_produccion_detalle_id' => $request->orden_produccion_detalle_id, 
        'articulo_id' => $request->articulo_id,        
        'cantidad_solicitada' => $request->cantidad_solicitada, 
        'cantidad_usada' => $request->cantidad_usada,         
        'cantidad_pendiente' => $request->cantidad_pendiente,  
        'cantidad_producida' => $request->cantidad_producida,  
        'usuario_modifica_id' => $request->usuario_modifica_id,  
        'maquina_id' => $request->maquina_id,  
        'hora_inicio' =>  $request->hora_inicio,  
        'hora_fin' =>  $request->hora_fin,
        'estado' => $request->estado,      
        'lote' => $request->lote,      
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ]);   
    
    if($id !== 0) {
      $res =  DB::table('orden_produccion_detalle')
      ->where('id', $request->input('orden_produccion_detalle_id'))  
      ->update([
        'cantidad_usada' => $request->cantidad_usada,
        'cantidad_existente' =>  $request->cantidad_pendiente,
        'usuario_modifica_id' => $request->usuario_modifica_id,             
        'updated_at' => date("Y-m-d H:i:s")]);
    }

    } catch (\Throwable $th) {
      return response()->json($th, "500");  
    }

  return response()->json($id, "200");  
}


public function updProduccionProceso(Request $request, $id){

  
  $tmp_fecha = str_replace('/', '-',  $request->hora_fin);
  $hora_fin =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 


   $res =  DB::table('produccion_proceso')
  ->where('id', $id)  
  ->update([        
    'lote' => $request->input('lote'),
    'maquina_id' => $request->input('maquina_id'), 
    'cantidad_producida' => $request->input('cantidad_producida'), 
    'cantidad_pendiente' => $request->input('cantidad_pendiente'),
    'cantidad_usada' => $request->input('cantidad_usada'),
    'hora_fin' => $hora_fin,
    'usuario_modifica_id' => $request->input('usuario_modifica_id'),           
    'estado' => $request->input('estado'),  
    'updated_at' => date("Y-m-d H:i:s")]); 

    if($id !== 0) {
      $res =  DB::table('orden_produccion_detalle')
      ->where('id', $id)  
      ->update([
        'cantidad_usada' => $request->input('cantidad_usada'),
        'cantidad_existente' =>  $request->input('cantidad_pendiente'),
        'usuario_modifica_id' => $request->input('usuario_modifica_id') ,             
        'updated_at' => date("Y-m-d H:i:s")]);
    }

    return response()->json($res, "200");
}






public function getProduccionProcesoByEstado(Request $request)
{  

  $estado = $request->estado;
    try {
      $res = DB::select( DB::raw("SELECT produccion_proceso.id, `orden_produccion_detalle_id`, produccion_proceso.articulo_id, produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_pendiente, `cantidad_producida`, produccion_proceso.usuario_modifica_id, `maquina_id`, `hora_fin`, `hora_inicio`, produccion_proceso.estado, orden_produccion_detalle.id as orden_produccion_detalle_id, orden_produccion_detalle.fecha_produccion, orden_produccion_detalle.cantidad_solicitada as  orden_produccion_detalle_cantidad_solicitada,
      orden_produccion_detalle.cantidad_usada AS orden_produccion_detalle_cantidad_usada, orden_produccion_detalle.cantidad_existente AS orden_produccion_detalle_cantidad_existente, articulo.nombre, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.unidades, articulo_propiedades.volumen, maquina.maquina_nombre  , lote
      FROM `produccion_proceso`, orden_produccion_detalle, orden_produccion, articulo,articulo_propiedades,  maquina 
      WHERE  produccion_proceso.orden_produccion_detalle_id = orden_produccion_detalle.id AND orden_produccion_detalle.orden_produccion_id = orden_produccion.id AND produccion_proceso.articulo_id = articulo.id AND maquina.id = produccion_proceso.maquina_id AND articulo.id = articulo_propiedades.articulo_id AND produccion_proceso.estado = '".$estado."'  LIMIT 100 ")
  );
    } catch (\Throwable $th) {
      return response()->json('ERROR INTERNO DEL SERVIDOR '.$th, "500");
    }


      return response()->json($res, "200");
}


/* -------------------------------------------------------------------------- */
/*            BUSCA LOS PROCESOS DE PRODUCCION POR FECHA DE INICIO            */
/* -------------------------------------------------------------------------- */

public function getProduccionProcesoByDates(Request $request)
{    
    $tmp_fecha = str_replace('/', '-',  $request->fecha_desde);
    $fecha_desde =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 
    $tmp_fecha = str_replace('/', '-',  $request->fecha_hasta);
    $fecha_hasta =  date('Y-m-d H:i:s', strtotime($tmp_fecha)); 

    try {
      $res = DB::select( DB::raw("SELECT produccion_proceso.id, `orden_produccion_detalle_id`, produccion_proceso.articulo_id, produccion_proceso.cantidad_solicitada, produccion_proceso.cantidad_usada, produccion_proceso.cantidad_pendiente, `cantidad_producida`, produccion_proceso.usuario_modifica_id, `maquina_id`, `hora_fin`, `hora_inicio`, produccion_proceso.estado, orden_produccion_detalle.id as orden_produccion_detalle_id, orden_produccion_detalle.fecha_produccion, orden_produccion_detalle.cantidad_solicitada as  orden_produccion_detalle_cantidad_solicitada,
      orden_produccion_detalle.cantidad_usada AS orden_produccion_detalle_cantidad_usada, orden_produccion_detalle.cantidad_existente AS orden_produccion_detalle_cantidad_existente, articulo.nombre, articulo_propiedades.pallet_pisos, articulo_propiedades.pallet_pack, articulo_propiedades.unidades, articulo_propiedades.volumen, maquina.maquina_nombre  , lote
FROM `produccion_proceso`, orden_produccion_detalle, orden_produccion, articulo,articulo_propiedades,  maquina 
WHERE  produccion_proceso.orden_produccion_detalle_id = orden_produccion_detalle.id AND orden_produccion_detalle.orden_produccion_id = orden_produccion.id AND produccion_proceso.articulo_id = articulo.id AND maquina.id = produccion_proceso.maquina_id AND articulo.id = articulo_propiedades.articulo_id  AND hora_inicio BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."'
   ")
  );
    } catch (\Throwable $th) {
      return response()->json('ERROR INTERNO DEL SERVIDOR '.$th, "500");
    }


      return response()->json($res, "200");
}

/* -------------------------------------------------------------------------- */
/*              ACTUALIZAR EL ESTADO DE UNAR ORDEN DE PRODUCCION              */
/* -------------------------------------------------------------------------- */

public function updOrdenProduccionProcesoEstado(Request $request){
  $res =  DB::table('orden_produccion_detalle')
  ->where('id', $request->input('orden_produccion_detalle_id'))  
  ->update([
    'estado' => $request->estado,             
    'updated_at' => date("Y-m-d H:i:s")]);
}

}
