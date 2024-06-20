<?php

namespace App\Http\Livewire\Form;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FormularioA;
use App\Models\Acumulacion;

class FormsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        // Obtener las acumulaciones actuales
        $acumulacion = Acumulacion::first();

        // Calcular las sumas totales y restar las acumulaciones
        $totalBebida = FormularioA::sum('bebida_cantidad') - $acumulacion->bebida_total;
        $totalComida = FormularioA::sum('comida_cantidad') - $acumulacion->comida_total;

        return view('livewire.form.forms-index', [
            'formularios' => FormularioA::paginate(10),
            'totalBebida' => $totalBebida,
            'totalComida' => $totalComida
        ]);
    }
}

