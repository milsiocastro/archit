<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class AdminCategories extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $showModal = 'hidden';
    public $category;
    public $open_edit = false;

    protected $listeners = ['render' => 'render', 'delete'];

    public function mount(){
        $this->category = new Category();
    }

    protected $rules = [
        'category.nombre' => 'required',
        'category.nombre_en' => 'required',
        'category.orden' => 'required',
        'category.estado' => 'required'
    ];

    public function render()
    {
        $categorias = Category::paginate(10);
        return view('livewire.admin-categories',[
            'categorias' => $categorias
        ]);
    }

    public function edit(Category $category){
        $this->category = $category;
        $this->open_edit = true;
    }

    public function update(){
        $this->validate();
        $this->category->slug = Str::slug($this->category->nombre, '-');
        $this->category->save();

        $this->reset(['open_edit']);

        $this->emit('alert', 'Categoría actualizada exitosamente');
    }

    public function delete(Category $category){
        $category->delete();
    }
}
