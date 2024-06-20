<?php

namespace App\Http\Livewire\Donar;

use Livewire\Component;
use App\Models\Donativo;
use App\Models\Product;
use Livewire\WithPagination;

class DonarIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $donativos = Donativo::with('user')->paginate(10);

        // Obtener los nombres de los poblados y asignarlos a los donativos
        foreach ($donativos as $donativo) {
            $product = Product::find($donativo->poblado);
            $donativo->nom_poblado = $product ? $product->nombre : 'No asignado';
        }

        return view('livewire.donar.donar-index', [
            'donativos' => $donativos
        ]);
    }
}

