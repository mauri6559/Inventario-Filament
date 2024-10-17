<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
    ];

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'id_tienda');
    }
}
