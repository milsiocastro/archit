<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioU extends Model
{
    use HasFactory;

    protected $table = 'formulario_usuario';

    protected $fillable = [
        'dni',
        'correo',
        'nombre',
        'apellido',
        'numero',
        'cantidad',
    ];
}
