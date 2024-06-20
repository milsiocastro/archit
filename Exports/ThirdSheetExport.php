<?php

namespace App\Exports;

use App\Models\Donativo;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithTitle;

class ThirdSheetExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Distribuciones';
    }

    public function collection()
    {
        // Obtener los datos de los donativos
        $donativos = Donativo::with('user')->get();

        // Formatear los datos para el Excel
        $data = $donativos->map(function ($donativo) {
            $product = Product::find($donativo->poblado);
            $nom_poblado = $product ? $product->nombre : 'No asignado';
            return [
                'nombre_usuario' => $donativo->user->name,
                'nom_poblado' => $nom_poblado,
                'cantidad_d' => $donativo->cantidad_d,
                'comida_cantidad_d' => $donativo->comida_cantidad_d,
                'bebida_cantidad_d' => $donativo->bebida_cantidad_d,
                'descripcion' => $donativo->descripcion,
                'created_at' => $donativo->created_at->format('Y-m-d'),
                'updated_at' => $donativo->updated_at->format('Y-m-d'),
            ];
        });

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Nombre Usuario',
            'Poblado',
            'Donativo (S/.)',
            'Stock de Comida',
            'Stock de Bebida',
            'Descripción',
            'Creación',
            'Actualización'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para los encabezados
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '95B3D7',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Estilo para las celdas
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 60,
            'G' => 20,
            'H' => 20,
        ];
    }
}
