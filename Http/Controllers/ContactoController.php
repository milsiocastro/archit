<?php

namespace App\Http\Controllers;

use App\Mail\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    protected $rules = [
        'nombres' => 'required',
        'apellidos' => 'required',
        'telefono' => 'required',
        'email' => 'required',
        'politicap' => 'required',
    ];

    public function sendForm(Request $request){
        $request->validate($this->rules);
        $datos = [
            "nombres" => $request->nombres,
            "apellidos" => $request->apellidos,
            "email" => $request->email,
            "telefono" => $request->telefono,
            "mensaje" => $request->mensaje
        ];
        Mail::to('patrick.vega@unmsm.edu.pe')
                ->send(new Contacto($datos));
        return redirect()->route('contacto.form')->with('msgSuccess', trans('lang.central.correo_enviado'));
    }
}
