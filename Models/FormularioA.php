<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioA extends Model
{
    use HasFactory;

    protected $table = 'formulario_a';

    protected $fillable = [
        'user_id',
        'correo_origen',
        'correo_destino',
        'asunto',
        'mensaje',
        'comida_cantidad',
        'bebida_cantidad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
