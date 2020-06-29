<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Articulo extends Model
{
    

  
    protected $table = 'articulo';

   protected $fillable = [
       
        'id',
        'descripcion',
        'unidad_id'        
        
    
    ];

}
