<?php

use Illuminate\Http\Request;
//require "../vendor/autoload.php";
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
//Auth::routes(['register' => false]);


/* -------------------------------------------------------------------------- */
/*                              RUTAS DE USUARIOS                             */
/* -------------------------------------------------------------------------- */


Route::name('user-info')->get('user/password', 'User\UserController@getPassword'); 
Route::name('user-info')->get('user/info/menu', 'User\UserController@getUserDataAndMenu'); 
Route::name('user-info')->get('user/menu', 'User\UserController@getMenu');
Route::name('user-info')->post('user/menu/add/{id}', 'User\UserController@agregarMenuUsuario');
Route::name('user-info')->delete('user/menu/{id}', 'User\UserController@borrarMenuUsuario');
Route::name('user-info')->get('user/listado', 'User\UserController@getUsuarios');
Route::name('user-info')->post('user/crear', 'User\UserController@CrearUsuario');
Route::name('user-info')->put('user/editar/{id}', 'User\UserController@EditarUsuario');
Route::name('user-info')->put('user/editar/password/{id}', 'User\UserController@EditarUsuarioPassword');
Route::resource('user', 'User\UserController');

/* -------------------------------------------------------------------------- */
/*                              RUTAS DE LLAMADO                             */
/* -------------------------------------------------------------------------- */


Route::name('turnos')->get('turnos/llamar/proximo', 'Turnos\TurnosController@getProximoNumero');
Route::name('turnos')->get('turnos/llamar/llamar', 'Turnos\TurnosController@Llamar');
Route::name('turnos')->get('turnos/llamar/llamar/repetir', 'Turnos\TurnosController@LlamarRepetir'); 
Route::name('turnos')->get('turnos/llamar/llamar/seleccionado', 'Turnos\TurnosController@llamarNumeroSeleccionado');
Route::name('turnos')->get('turnos/llamar/pantalla', 'Turnos\TurnosController@getListadoPantalla'); 
Route::name('turnos')->get('turnos/pantalla/llamando', 'Turnos\TurnosController@getLlamando');
Route::name('turnos')->get('turnos/consulta/condicion/estado', 'Turnos\TurnosController@getListadoSectorCondicion'); 

Route::name('turnos-gestion')->post('turnos/numero/nuevo', 'Turnos\TurnosController@setNumero'); 
Route::name('turnos-gestion')->get('turnos/usuario/sector', 'Turnos\TurnosController@getSectorByUsuario');





/* -------------------------------------------------------------------------- */
/*                                 MULTIMEDIA                                 */
/* -------------------------------------------------------------------------- */

Route::name('turnos-gestion')->get('multimedia/ordenado', 'Multimedia\MultimediaController@getMultimedia');


/* --------------------------------------------------------------------------  */
/*                             RUTAS DE MANTENIMIENTO                          */
/* -------------------------------------------------------------------------- */


Route::name('mantenimiento')->get( 'mantenimiento/sector', 'Mantenimiento\MantenimientoController@getSector');
Route::name('mantenimiento')->post('mantenimiento/sector', 'Mantenimiento\MantenimientoController@setSector');
Route::name('mantenimiento')->put( 'mantenimiento/sector/{id}', 'Mantenimiento\MantenimientoController@updSector');

Route::name('mantenimiento')->get( 'mantenimiento/puesto', 'Mantenimiento\MantenimientoController@getpuesto');
Route::name('mantenimiento')->post('mantenimiento/puesto', 'Mantenimiento\MantenimientoController@setpuesto');
Route::name('mantenimiento')->put( 'mantenimiento/puesto/{id}', 'Mantenimiento\MantenimientoController@updpuesto');


Route::name('mantenimiento')->get( 'mantenimiento/sector/usuario', 'Mantenimiento\MantenimientoController@getSectorUsuario');
Route::name('mantenimiento')->get('mantenimiento/sector/usuario/nuevo', 'Mantenimiento\MantenimientoController@setSectorUsuario');
Route::name('mantenimiento')->put( 'mantenimiento/sector/usuario/{id}', 'Mantenimiento\MantenimientoController@updSectorUsuario');

Route::name('mantenimiento')->get( 'mantenimiento/sector/usuario/asociar', 'Mantenimiento\MantenimientoController@getSectorUsuarioAsociado');
Route::name('mantenimiento')->post('mantenimiento/sector/usuario/asociar', 'Mantenimiento\MantenimientoController@setSectorUsuarioAsociado');
Route::name('mantenimiento')->put( 'mantenimiento/sector/usuario/asociar/{id}', 'Mantenimiento\MantenimientoController@updSectorUsuarioAsociado');
Route::name('mantenimiento')->delete('mantenimiento/sector/usuario/asociar/{id}', 'Mantenimiento\MantenimientoController@delSectorUsuarioAsociado');


Route::name('mantenimiento')->get( 'mantenimiento/regla', 'Mantenimiento\MantenimientoController@getRegla');
Route::name('mantenimiento')->post('mantenimiento/regla', 'Mantenimiento\MantenimientoController@setRegla');
Route::name('mantenimiento')->put( 'mantenimiento/regla/{id}', 'Mantenimiento\MantenimientoController@updRegla');

/* --------------------------------------------------------------------------  */
/*                             RUTAS DE ARCHIVOS                          */
/* -------------------------------------------------------------------------- */
Route::name('archivos')->post('/multiuploads/multimedia', 'Upload\UploadController@showUploadFile');
Route::name('archivos')->post('/multiuploads/multimedia/datos', 'Upload\UploadController@UploadFileDatos');  
Route::name('archivos')->put('/multiuploads/multimedia/datos/{id}', 'Upload\UploadController@UploadFileDatosUpdate');

/** CHAT **/
