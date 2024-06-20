<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acumulacion extends Model
{
    use HasFactory;

    protected $table = 'acumulaciones';

    protected $fillable = ['cantidad_total', 'comida_total', 'bebida_total'];
}
