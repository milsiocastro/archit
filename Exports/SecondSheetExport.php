<?php

namespace App\Exports;

use App\Models\FormularioU;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithTitle;

class SecondSheetExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Donaciones Usuarios';
    }

    public function collection()
    {
        // Obtener los datos de las donaciones de los usuarios
        $donaciones = FormularioU::all();

        // Formatear los datos para el Excel
        $data = $donaciones->map(function ($donacion) {
            return [
                'dni' => $donacion->dni,
                'correo' => $donacion->correo,
                'nombre' => $donacion->nombre,
                'apellido' => $donacion->apellido,
                'numero' => $donacion->numero,
                'cantidad' => $donacion->cantidad,
                'created_at' => $donacion->created_at->format('Y-m-d'),
                'updated_at' => $donacion->updated_at->format('Y-m-d'),
            ];
        });

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Correo',
            'Nombre',
            'Apellido',
            'Número',
            'Cantidad Donada',
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
            'C' => 30,
            'D' => 30,
            'E' => 18,
            'F' => 18,
            'G' => 20,
            'H' => 20,
        ];
    }
}

