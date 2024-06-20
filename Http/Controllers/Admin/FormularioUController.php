<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormularioU;
use App\Models\Acumulacion;

class FormularioUController extends Controller
{
    public function index()
    {
        $donaciones = FormularioU::paginate(10);

        // Obtener las acumulaciones actuales
        $acumulacion = Acumulacion::first();

        // Calcular la suma total de todas las donaciones y restar la acumulación
        $totalDonaciones = FormularioU::sum('cantidad') - $acumulacion->cantidad_total;

        return view('admin.donaciones', compact('donaciones', 'totalDonaciones'));
    }
}
