<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Donativo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithTitle;

class FourthSheetExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    
    public function title(): string
    {
        return 'Totales por Poblado';
    }

    public function collection()
    {
        // Obtener los datos de los productos y sus donativos
        $products = Product::all();
        $data = $products->map(function ($product) {
            $total_donations = Donativo::where('poblado', $product->id)->get();
            return [
                'nombre' => $product->nombre,
                'total_dinero' => $total_donations->sum('cantidad_d'),
                'total_comida' => $total_donations->sum('comida_cantidad_d'),
                'total_bebida' => $total_donations->sum('bebida_cantidad_d')
            ];
        });

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Nombre del Poblado',
            'Total Dinero',
            'Total Comida',
            'Total Bebida'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para los encabezados
        $sheet->getStyle('A1:D1')->applyFromArray([
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
        $sheet->getStyle('A1:D' . $sheet->getHighestRow())->applyFromArray([
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
            'A' => 30,
            'B' => 20,
            'C' => 20,
            'D' => 20,
        ];
    }

}
