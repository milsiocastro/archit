<?php

namespace App\Exports;

use App\Models\FormularioA;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithTitle;

class FirstSheetExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{

    public function title(): string
    {
        return 'Solicitudes';
    }

    public function collection()
    {
        // Obtener los datos de las ONGs
        $formularios = FormularioA::all();

        // Formatear los datos para el Excel
        $data = $formularios->map(function ($formulario) {
            return [
                'nombre_usuario' => $formulario->user->name,
                'correo_origen' => $formulario->correo_origen,
                'correo_destino' => $formulario->correo_destino,
                'asunto' => $formulario->asunto,
                'mensaje' => $formulario->mensaje,
                'comida_cantidad' => $formulario->comida_cantidad ?? 'No se registró Comida para esta solicitud',
                'bebida_cantidad' => $formulario->bebida_cantidad ?? 'No se registró Bebida para esta solicitud',
                'created_at' => $formulario->created_at->format('Y-m-d'),
                'updated_at' => $formulario->updated_at->format('Y-m-d'),
            ];
        });

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Nombre Usuario',
            'Correo Origen',
            'Correo Destino',
            'Asunto',
            'Mensaje',
            'Comida',
            'Bebida',
            'Creación',
            'Actualización'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para los encabezados
        $sheet->getStyle('A1:I1')->applyFromArray([
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
        $sheet->getStyle('A1:I' . $sheet->getHighestRow())->applyFromArray([
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
            'E' => 60,
            'F' => 40,
            'G' => 40,
            'H' => 20,
            'I' => 20,
        ];
    }
}
