<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'nombre_en', 'orden', 'estado', 'slug'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
