<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donativo extends Model
{
    use HasFactory;

    protected $table = 'donativo';

    protected $fillable = [
        'user_id', 'poblado', 'cantidad_d', 'bebida_cantidad_d', 'comida_cantidad_d', 'descripcion'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poblado()
    {
        return $this->belongsTo(Product::class, 'poblado', 'id');
    }
}
