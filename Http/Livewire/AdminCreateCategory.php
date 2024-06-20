<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminCreateCategory extends Component
{

    public $open = false;

    public $nombre, $nombre_en, $orden;

    protected $rules = [
        'nombre' => 'required',
        'nombre_en' => 'required',
        'orden' => 'required',
    ];

    public function save(){

        $this->validate();

        Category::create([
            'nombre' => $this->nombre,
            'nombre_en' => $this->nombre_en,
            'orden' => $this->orden,
            'estado' => 'Activo',
            'slug' => Str::slug($this->nombre, '-')
        ]);

        $this->reset(['open','nombre','nombre_en','orden']);

        $this->emitTo('admin-categories','render');
        $this->emit('alert', 'Categoría creada exitosamente');
    }
    
    public function render()
    {
        return view('livewire.admin-create-category');
    }
}

