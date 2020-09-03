<?php

namespace App\Http\Controllers\Upload;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    //


    public function uploadSubmit(Request $request)
    {

     /*   $this->validate($request, [
        'name' => 'required',
        'photos'=>'required',
        ]);
    if($request->hasFile('photos'))
    {
        $allowedfileExtension=['pdf','jpg','png','docx'];
        $files = $request->file('photos');
        foreach($files as $file){
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $check=in_array($extension,$allowedfileExtension);
        //dd($check);
    if($check)
    {
        $items= Item::create($request->all());
        foreach ($request->photos as $photo) {
            $filename = $photo->store('photos');
            ItemDetail::create([
            'item_id' => $items->id,
            'filename' => $filename
            ]);
         }
         return response()->json('subido con éxito', 201);
    }
    else
    {
        return response()->json($request, 201);
    
    }
    }
    }*/
    return response()->json($request, 201);
    }



    public function showUploadFile(Request $request) {
        
$fecha = date("Y-m-d-Hi");
$allowedfileExtension=['MP4','jpg','png','jpeg','avi'];
$files = $request->file('images');

$filename = $files->getClientOriginalName();
$extension = $files->getClientOriginalExtension();
//$check=in_array($extension,$allowedfileExtension);
$destinationPath = 'uploads/';
$without_extension = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
  echo $filename;
  echo $extension;
  echo $destinationPath;  

$files->move($destinationPath,$filename);



        return response()->json("Upload Successfully", 201);
     }


     public function showUploadFileDatos(Request $request){
        $fecha = date("Y-m-d-Hi");
        $t =$request;
        $subcarpeta = 
        $tmp_fecha = str_replace('/', '-', $t[0]["fecha_estudio"]);
         $fecha_estudio =  date('Y-m-d H:i', strtotime($tmp_fecha));
    $id =    DB::table('estudio')->insertGetId(
         ['estudio' => $t[0]["estudio"],
        'paciente_id' => $t[0]["paciente_id"],
         'medico_id' => $t[0]["medico_id"],
         'fecha_estudio' => $fecha_estudio,
         'usuario_realiza_id' => $t[0]["usuario_realiza_id"],
         'updated_at' => date("Y-m-d H:i:s"),
         'created_at' => date("Y-m-d H:i:s")
         ]           
        );
        $i = 0;
        while(isset($t[$i])){
            
      //  $usuario_id=$t[$i]["usuario_id"];
    //    $tmp_fecha = str_replace('/', '-', $t[$i]["fecha"]);
       // $fecha_desde =  date('Y-m-d H:i', strtotime($tmp_fecha));   
         $estudio_id =    DB::table('estudios_imagen')->insertGetId(             
            [
            'estudio_id' => $id,
            'nombre' => $t[$i]["file_name"],
            'file' => $t[$i]["file"],
            'url' => 'uploads/'.$fecha,
            'updated_at' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s")
             ]           
            );   
            $i++;
        }    

        $FICHA_id =    DB::table('ficha')->insertGetId(
            [
           'paciente_id' => $t[0]["paciente_id"],
            'PACIENTE' => $t[0]["paciente_dni"],
            'SINTOMAS_SIGNOS' => $t[0]["SINTOMAS_SIGNOS"],
            'MEDICONOM' => 'ESTUDIOS',
            'MEDICO' => 'SIS-NU',
            'FECHA' => $fecha_estudio
            
            ]           
           );

           $FICHAOFTALMO_id =    DB::table('fichaoftal01')->insertGetId(
            ['estudio_id' => $id,          
            'estudio_nombre' => $t[0]["estudio"],
            'NUMERO' => $FICHA_id,
            'updated_at' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s")
            ]           
           );
        return response()->json($t, "201");
    }


    public function getEstudioImagenes(Request $request){
        $id =$request->input('id');

        $horario = DB::select( DB::raw("SELECT estudio.id as estudio_id, estudio.paciente_id , fecha_estudio, nombre, file, url, estudio FROM `estudio`, estudios_imagen WHERE  estudio.id = estudios_imagen.estudio_id AND estudio.id = ".$id."
    "));
       
      return response()->json($horario, 201);

    }
    public function getLocalStoragePath(){
   // echo Storage::disk('local')->url($fileName);
}
}
