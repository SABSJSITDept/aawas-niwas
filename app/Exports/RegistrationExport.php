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

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Type',          
            'Booking ID',
            'Name',
            'Father Name',
            'Phone',
            'Aadhar Number',
            'Age',
            'MID',            
            'City',
            'State',
            'Aanchal',
            'Travel Type',
            'Check-in Date',
            'Check-in Time',
            'Check-out Date',
            'Check-out Time',
            'Total Persons'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Type
            'B' => 15,  // Booking ID
            'C' => 20,  // Name
            'D' => 20,  // Father Name
            'E' => 15,  // Phone
            'F' => 18,  // Aadhar Number
            'G' => 10,  // Age
            'H' => 12,  // MID
            'I' => 15,  // City
            'J' => 15,  // State
            'K' => 15,  // Aanchal
            'L' => 15,  // Travel Type
            'M' => 15,  // Check-in Date
            'N' => 15,  // Check-out Date
            'O' => 15,  // Total Persons
        ];
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