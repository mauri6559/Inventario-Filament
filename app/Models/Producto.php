<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Producto extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_unit_compra',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
