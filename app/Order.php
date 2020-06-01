<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //guarded es para especificar los atributos que no se pueden inyectar a traves del modelo,
    //si esta vacio significa que se pueden meter todos los atributos.
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany('\App\Product')->withPivot('units');
    }
    public function user()
    {
        return $this->belongsTo('\App\user');
    }
}
