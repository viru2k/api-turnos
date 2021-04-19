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
$allowedfileExtension=['MP4','mp4','jpg','JPG','PNG','png','jpeg','JPEG','avi', 'AVI'];
$files = $request->file('images');

//$filename = $files->getClientOriginalName();
$extension = $files->getClientOriginalExtension();
$check=in_array($extension,$allowedfileExtension);
if($check){
  $destinationPath = 'uploads/';
$without_extension = pathinfo($fecha.$files->getClientOriginalName(), PATHINFO_FILENAME);
  $files->move($destinationPath,$without_extension.'.'.$extension);
}else {
  return response()->json("Extension erronea", 301);
}

 // echo $filename;
 // echo $extension;
 // echo $destinationPath;





        return response()->json($without_extension.'.'.$extension, 201);
     }


     public function UploadFileDatos(Request $request){

    $id =    DB::table('multimedia')->insertGetId([
         'archivo_nombre' => $request["archivo_nombre"],
         'archivo_nombre_original' => $request["archivo_nombre_original"],
         'archivo_descripcion' => $request["archivo_descripcion"],
         'orden' => $request["orden"],
         'fecha_carga' => $request["fecha_carga"],
         'fecha_vencimiento' => $request["fecha_vencimiento"]
          ]);

        return response()->json($id, "201");
    }


    public function UploadFileDatosUpdate(Request $request, $id){
   //  echo  $request->input('tiene_vencimiento');
    $res =  DB::table('multimedia')
    ->where('id', $id)
    ->update([
      'archivo_nombre' => $request->input('archivo_nombre'),
      'archivo_nombre_original' => $request->input('archivo_nombre_original'),
      'archivo_descripcion' => $request->input('archivo_descripcion'),
      'orden' => $request->input('orden'),
      'fecha_carga' => $request->input('fecha_carga'),
      'fecha_vencimiento' => $request->input('fecha_vencimiento'),
      'tiene_vencimiento' => $request->input('tiene_vencimiento')
     ]);
     return response()->json($res, "201");
}
}
