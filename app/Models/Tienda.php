<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tienda extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nombre',
        'direccion',
    ];

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'id_tienda');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'text']);
    }
}
