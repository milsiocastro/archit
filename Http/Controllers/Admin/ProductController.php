<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    protected $rules = [
        'nombre' => 'required',
        'descripcion' => 'required',
        'imagen1' => 'image|nullable',
        'imagen2' => 'image|nullable',
        'imagen3' => 'image|nullable',
        'imagen4' => 'image|nullable',
        'categoria'=>'required',
    ];

    public function index()
    {
        // Si el usuario es un administrador, muestra todos los productos
        if (auth()->user()->utype === 'ADMIN') {
            $products = Product::all();
        } else {
            // Si no, solo muestra los productos del usuario
            $products = Product::where('user_id', auth()->user()->id)->get();
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('nombre', 'id');
        $type = 'create';
        return view('admin.products.create', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        $reglas = $this->rules;
        $reglas['imagen'] = 'required|image';
        $request->validate($reglas);

        $product=new Product();
        $product->nombre = $request->nombre;
        $product->nombre_en = $request->nombre_en;
        $product->descripcion = $request->descripcion;
        $product->descripcion_en = $request->descripcion_en;
        $product->estado = 1;
        $product->categoria = $request->categoria;

        $product->user_id = auth()->user()->id;

        //IMAGEN PRINCIPAL
        $product->imagen = $this->saveImagen($request->imagen);

        //IMAGEN 1
        if(!is_null($request->imagen1)){
            $product->imagen1 = $this->saveImagen($request->imagen1);
        }

        //IMAGEN 2
        if(!is_null($request->imagen2)){
            $product->imagen2 = $this->saveImagen($request->imagen2);
        }

        //IMAGEN 3
        if(!is_null($request->imagen3)){
            $product->imagen3 = $this->saveImagen($request->imagen3);
        }

        //IMAGEN 4
        if(!is_null($request->imagen4)){
            $product->imagen4 = $this->saveImagen($request->imagen4);
        }

        $product->save();

        if (auth()->user()->utype === 'ADMIN') {
            return redirect()->route('admin.products.index')->with('msgSuccess', 'Información registrada exitosamente');
        } else {
            // Si no, solo muestra los productos del usuario
            return redirect()->route('user.products.index')->with('msgSuccess', 'Información registrada exitosamente');
        }
        //return redirect()->route('products.edit', $product);
    }

    public function show(Product $product)
    {
        // Asegúrate de que el usuario es el propietario del producto o un administrador
        if (auth()->user()->id !== $product->user_id && auth()->user()->utype !== 'ADMIN') {
            return redirect()->back()->with('error', 'No tienes permiso para hacer eso.');
        }

        return view('admin.products.show', compact('product', 'categories'));
    }

    public function edit(Product $product)
    {
        // Asegúrate de que el usuario es el propietario del producto o un administrador
        if (auth()->user()->utype !== 'ADMIN' && auth()->user()->id != $product->user_id) {
            return redirect()->back()->with('error', 'No tienes permiso para hacer eso.');

        }

        $categories = Category::pluck('nombre', 'id');
        $type = 'edit';

        // Redirige a la vista correcta basándote en el tipo de usuario
        if (auth()->user()->utype === 'ADMIN') {
            return view('admin.products.edit', compact('product', 'categories', 'type'));
        } else {
            return view('user.products.edit', compact('product', 'categories', 'type'));
        }
    }

    public function update(Request $request, Product $product)
    {
        $reglas = $this->rules;
        $reglas['imagen'] = 'image|nullable';
        $request->validate($reglas);

        $product->nombre = $request->nombre;
        $product->nombre_en = $request->nombre_en;
        $product->descripcion = $request->descripcion;
        $product->descripcion_en = $request->descripcion_en;
        $product->estado = $request->estado;
        $product->categoria = $request->categoria;

        //IMAGEN PRINCIPAL
        if(!is_null($request->imagen)){
            if(!is_null($product->imagen)){
                $this->deleteImagen($product->imagen);
            }
            $product->imagen = $this->saveImagen($request->imagen);
        }

        //IMAGEN 1
        if(!is_null($request->imagen1)){
            if(!is_null($product->imagen1)){
                $this->deleteImagen($product->imagen1);
            }
            $product->imagen1 = $this->saveImagen($request->imagen1);
        } else if ($request->imagen2_eliminar == '1') {
            if(!is_null($product->imagen1)){
                $this->deleteImagen($product->imagen1);
            }
            $product->imagen1 = null;
        }

        //IMAGEN 2
        if(!is_null($request->imagen2)){
            if(!is_null($product->imagen2)){
                $this->deleteImagen($product->imagen2);
            }
            $product->imagen2 = $this->saveImagen($request->imagen2);
        } else if ($request->imagen3_eliminar == '1') {
            if(!is_null($product->imagen2)){
                $this->deleteImagen($product->imagen2);
            }
            $product->imagen2 = null;
        }

        //IMAGEN 3
        if(!is_null($request->imagen3)){
            if(!is_null($product->imagen3)){
                $this->deleteImagen($product->imagen3);
            }
            $product->imagen3 = $this->saveImagen($request->imagen3);
        } else if ($request->imagen4_eliminar == '1') {
            if(!is_null($product->imagen3)){
                $this->deleteImagen($product->imagen3);
            }
            $product->imagen3 = null;
        }

        //IMAGEN 4
        if(!is_null($request->imagen4)){
            if(!is_null($product->imagen4)){
                $this->deleteImagen($product->imagen4);
            }
            $product->imagen4 = $this->saveImagen($request->imagen4);
        } else if ($request->imagen5_eliminar == '1') {
            if(!is_null($product->imagen4)){
                $this->deleteImagen($product->imagen4);
            }
            $product->imagen4 = null;
        }


        $product->save();

        if (auth()->user()->utype === 'ADMIN') {
            return redirect()->route('admin.products.index')->with('msgSuccess', 'Información actualizada');
        } else {
            // Si no, solo muestra los productos del usuario
            return redirect()->route('user.products.index')->with('msgSuccess', 'Información actualizada');
        }
    }

    public function destroy(Product $product)
    {
        
    }

    private function saveImagen($imagen){
        $nombre = date('Ymdhis') .rand(). '.' . $imagen->extension();
        $ruta = storage_path().'/app/public/imagenes/' . $nombre;
        $productImg = 'storage/imagenes/' . $nombre;

        $img = Image::make($imagen);
        $width = $img->width();

        if($width>500 || $width<100){
            $img->resize(500, null, function($constraint){$constraint->aspectRatio();})->save($ruta);
        } else{
            $img->save($ruta);
        }

        return $productImg;
    }

    private function deleteImagen($imagen){
        $url = str_replace('storage', 'public', $imagen);
        Storage::delete($url);
    }
}
