<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductsIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $auxcategoria;
    public $open_imagen = false;
    public $imagenProducto;

    protected $listeners = ['render' => 'render', 'delete'];

    public function mount(){
        $this->product = new Product();
        $this->imagenProducto = new Product();
        $this->auxcategoria = new Category();
    }

    public function render()
    {
        // Si el usuario es un administrador, muestra todos los productos
        if (auth()->user()->utype === 'ADMIN') {
            $products = Product::paginate(10);
        } else {
            // Si no, solo muestra los productos del usuario
            $products = Product::where('user_id', auth()->user()->id)->paginate(10);
        }

        return view('livewire.product.products-index', compact('products'));
    }


    public function delete(Product $product){
        $url = str_replace('storage', 'public', $product->imagen);
        Storage::delete($url);
        $url_1 = str_replace('storage', 'public', $product->imagen1);
        Storage::delete($url_1);
        $url_2 = str_replace('storage', 'public', $product->imagen2);
        Storage::delete($url_2);
        $url_3 = str_replace('storage', 'public', $product->imagen3);
        Storage::delete($url_3);
        $url_4 = str_replace('storage', 'public', $product->imagen4);
        Storage::delete($url_4);
        $product->delete();
    }

    public function verImagen(Product $product){
        $this->imagenProducto = $product;
        $this->open_imagen = true;
    }
}
