<?php

namespace App\Http\Controllers\Produccion;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\models\Maquina;
class MaquinaController extends ApiController
{
    public function index()
    {
        $Maquina = Maquina::all();
        return $this->showAll($Maquina);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'maquina_nombre' => 'required'
            
           
        ];

        $this->validate($request, $rules);
        $resp = Maquina::create($request->all());
        return $this->showOne($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Maquina  $Maquina
     * @return \Illuminate\Http\Response
     */
    public function show(Maquina $Maquina)
    {
        return $this->showOne($Maquina);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Maquina  $Maquina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Maquina = Maquina::findOrFail($id);
        $Maquina->fill($request->only([
            'maquina_nombre'                         
    ]));

   if ($Maquina->isClean()) {
        return $this->errorRepsonse('Se debe especificar al menos un valor', 422);
    }
   $Maquina->save();
    return $this->showOne($Maquina);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Maquina  $Maquina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maquina $Maquina)
    {
        //
    }
}
