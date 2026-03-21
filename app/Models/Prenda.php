<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prenda extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'talla',
        'color',
        'imagen',
        'categoria',
        'tipo',
    ];

    public function tallas()
    {
        return $this->hasMany(PrendaTalla::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}
