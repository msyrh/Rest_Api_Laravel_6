<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table='products';
    protected $fillable=['id_category','nama','harga','image','qty'];
    protected $hidden=['created_at','updated_at'];

    public function category()
    {
    	return $this->belongsTo(category::class,'id_category','id');
    }
}

