<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'categoria');
    }
    public function donativos(){
        return $this->hasMany(Donativo::class, 'poblado');
    }
}
