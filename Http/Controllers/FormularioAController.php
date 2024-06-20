<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormularioA;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormularioAMail;
use Illuminate\Support\Facades\Auth;


class FormularioAController extends Controller
{
    public function create()
    {
        $admin_name = Auth::user()->name;
        $user_email = Auth::user()->email;

        return view('admin.correos.create', compact('admin_name', 'user_email'));
    }

    public function index()
    {
        $formularios = FormularioA::all();

        return view('admin.correos.index', compact('formularios'));
    }

    public function show($id) 
    {
        $formulario = FormularioA::with('user')->findOrFail($id); 
        $nombre_usuario = $formulario->user->name; 
        return view('admin.correos.show', compact('nombre_usuario', 'formulario'));
    }
    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'correo_origen' => 'required|email',
            'correo_destino' => 'required|email',
            'asunto' => 'required|string',
            'mensaje' => 'required|string',
            'comida_cantidad' => 'nullable|numeric',
            'bebida_cantidad' => 'nullable|numeric',
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $formularioA = FormularioA::create($validatedData);

        Mail::to($validatedData['correo_destino'])->send(new FormularioAMail($formularioA));

        return redirect()->route('admin.correos.index')->with('msgSuccess', 'Correo enviado exitosamente');
    }

}
