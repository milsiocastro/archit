<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioU;

class FormularioUController extends Controller
{
    public function index()
    {
        return view('pages.donaciones');
    }


    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|integer',
            'correo' => 'required|email',
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'numero' => 'required|integer',
            'cantidad' => 'required|integer',
        ]);

        FormularioU::create($request->all());

        return redirect()->route('donaciones.index')->with('msgSuccess', 'Formulario enviado correctamente.');
    }
}
