<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    
    protected $fillable = ['nama'];
    protected $hidden=['created_at','updated_at'];
    
}
