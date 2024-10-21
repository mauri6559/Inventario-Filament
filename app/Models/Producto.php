<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'id_Categoria',
        'id_marca',
    ];

    public function categoria() 
    {
        return $this->belongsTo(Categoria::class, 'id_Categoria');
    }

    public function marca() 
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }
    
    public function inventarios() 
    {
        return $this->belongsTo(Inventario::class, 'id_producto');
    }
}
