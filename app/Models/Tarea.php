<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    // Campos que se pueden rellenar mediante create() o update()
    protected $fillable = [
        'titulo',
        'descripcion',
        'user_id',
        'completada',
        'categoria'
    ];

    public function user() {
        // Cada tarea pertenece a un único usuario
        return $this->belongsTo(User::class); // $this: objeto actual de la tarea
    } // belongsTo(): tabla q tiene una relación de pertenencia
} // User (modelo), ::class (devuelve el nombre completo de la clase como string)
