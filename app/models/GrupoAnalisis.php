<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class GrupoAnalisis extends Model
{
    

  
    protected $table = 'grupo_analisis';

   protected $fillable = [
       
        'id',
        'grupo_nombre',
        'color'
        
    
    ];

}
