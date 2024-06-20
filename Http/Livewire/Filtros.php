<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Filtros extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $categoria;
    public $selectedCategory = null;
    protected $queryString = ['categoria' => ['except' => ''], 'page'];

    public function mount(){
        if($this->categoria != null){
            $aux = Category::where('slug', $this->categoria)->get();
            if(count($aux) > 0){
                $this->selectedCategory = $aux[0]->id;
            }
        }
    }

    public function render()
    {
        if($this->selectedCategory != ''){
            $categoria = Category::find($this->selectedCategory);
            if($categoria && $categoria->estado == 'Activo'){
                $productos = Product::where([['categoria', $this->selectedCategory], ['estado', 1]]);
            }
        }else{
            // Obtén solo los productos cuya categoría está activa
            $productos = Product::whereHas('category', function ($query) {
                $query->where('estado', 'Activo');
            })->where('estado', 1);
        }
    
        // Aplica la búsqueda por nombre si se ha ingresado un texto de búsqueda
        if($this->search != ''){
            $productos = $productos->where('nombre', 'like', '%' . $this->search . '%');
            $this->resetPage();
        }
    
        // Pagina los resultados
        $productos = $productos->paginate(6);
    
        return view('livewire.filtros', [
            'categorias' => Category::where('estado', 'Activo')->get(),
            'productos' => $productos
        ]);
    }
    
    
    public function updatedselectedCategory($category_id){
        if($category_id != ''){
            $this->categoria = Category::find($category_id)->slug;
        } else {
            $this->categoria = '';
        }
    }

    public function setValueCategory($id){
        if($id != '' && $id != $this->selectedCategory){
            $this->categoria = Category::find($id)->slug;
            $this->selectedCategory = $id;
            $this->resetPage();
            $this->dispatchBrowserEvent('category-changed', ['id' => $id]);
            $this->resetPage();
        } else {
            $this->categoria = '';
            $this->selectedCategory = null;
            $this->dispatchBrowserEvent('category-changed', ['id' => null]);
            $this->resetPage();
        }
    }
    
    
}