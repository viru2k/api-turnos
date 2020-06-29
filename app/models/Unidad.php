<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Unidad extends Model
{
    

  
    protected $table = 'unidad';

   protected $fillable = [
       
        'id',
        'descripcion'
        
    
    ];

}
