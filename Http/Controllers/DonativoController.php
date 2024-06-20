<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donativo;
use App\Models\Product;
use App\Models\FormularioU;
use App\Models\FormularioA;
use App\Models\Acumulacion;
use Illuminate\Support\Facades\Auth;

class DonativoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $donativos = Donativo::with('poblado', 'user')->paginate(10); // Carga los donativos con sus productos y usuarios relacionados
        return view('admin.donar.index', compact('donativos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $admin_name = auth()->user()->name; // Asegúrate de que esta variable esté definida
        $products = Product::all(); // Obtiene todos los productos para el selector
        return view('admin.donar.create', compact('admin_name', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'poblado' => 'required|exists:product,id',
            'cantidad_d' => 'nullable|integer',
            'bebida_cantidad_d' => 'nullable|numeric',
            'comida_cantidad_d' => 'nullable|numeric',
            'descripcion' => 'required|string'
        ]);

        $donativo = new Donativo($request->all());
        $donativo->poblado = $request->poblado;
        $donativo->user_id = Auth::id();
        $donativo->save();

        // Obtener la acumulación actual
        $acumulacion = Acumulacion::first();

        // Sumar las cantidades donadas a las acumulaciones
        if ($donativo->cantidad_d) {
            $acumulacion->cantidad_total += $donativo->cantidad_d;
        }

        if ($donativo->comida_cantidad_d) {
            $acumulacion->comida_total += $donativo->comida_cantidad_d;
        }

        if ($donativo->bebida_cantidad_d) {
            $acumulacion->bebida_total += $donativo->bebida_cantidad_d;
        }

        // Guardar las nuevas acumulaciones
        $acumulacion->save();

        // Actualizar la ayuda recibida en el poblado
        $poblado = Product::find($request->poblado);
        if ($poblado) {
            $poblado->ayuda_recibida = ($poblado->ayuda_recibida ?? 0) + ($donativo->cantidad_d ?? 0);
            $poblado->save();
        }

        return redirect()->route('admin.donar.index')->with('msgSuccess', 'Donativo registrado exitosamente.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $donativo = Donativo::with('user')->findOrFail($id);
        $product = Product::find($donativo->poblado);
        $donativo->nom_poblado = $product ? $product->nombre : 'No asignado';

        return view('admin.donar.show', compact('donativo'));
    }
}
