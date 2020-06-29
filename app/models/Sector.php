<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Sector extends Model
{
    

  
    protected $table = 'sector';

   protected $fillable = [
       
        'id',
        'nombre',
        'cantidad_personal'
        
    
    ];

}
