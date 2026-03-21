<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'prenda_id',
        'cantidad',
        'precio',
        'talla',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
