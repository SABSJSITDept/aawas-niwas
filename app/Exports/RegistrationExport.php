<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RegistrationExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths
{
    protected $data;
    protected $headings;

    public function __construct(array $data, array $headings = [])
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        // If dynamic headings provided, use them; otherwise extract from first row keys
        if (!empty($this->headings)) {
            return $this->headings;
        }

        if (!empty($this->data)) {
            $firstRow = reset($this->data);
            if (is_array($firstRow)) {
                return array_map('ucwords', array_map(function($key) {
                    return str_replace('_', ' ', $key);
                }, array_keys($firstRow)));
            }
        }

        return [];
    }

    public function columnWidths(): array
    {
        // We'll just provide a default width for all dynamically generated columns
        $widths = [];
        $headers = $this->headings();
        $col = 'A';
        foreach ($headers as $header) {
            $widths[$col] = 18; // Default width
            $col++;
        }
        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        // Set font for all cells to support Hindi/Unicode characters
        $sheet->getStyle('A:O')->getFont()->setName('Arial Unicode MS');
        $sheet->getStyle('A:O')->getFont()->setSize(11);
        
        // Style the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Arial Unicode MS',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        
        $sheet->getStyle('1:1')->applyFromArray($headerStyle);
        
        // Apply text wrapping to all data cells
        $sheet->getStyle('A2:O' . (count($this->data) + 1))
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);
        
        return [];
    }
}