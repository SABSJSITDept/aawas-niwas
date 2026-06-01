<?php

namespace App\Exports;

use App\Models\Form;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class FormsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return Form::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'M-ID',
            'Aadhar Number',
            'City',
            'State',
            'Aanchal',
            'Post',
            'Department',
            'Coming',
            'Stay Arrangement',
            'Travel Type',
            'Check-In Date',
            'Check-Out Date',
            'Check-In Time',
            'Check-Out Time',
            'Created At',
            'Updated At',
        ];
    }

    public function map($form): array
    {
        return [
            $form->id,
            $form->name,
            $form->phone,
            $form->member_id,
             $form->aadhar_number,
            $form->city,
            $form->state,
            $form->aanchal,
            $form->post,
            $form->department,
            $form->is_coming,
            $form->stay_arrangement,
            $form->travel_type,
            $form->check_in_date,
            $form->check_out_date,
            $form->check_in_time,
            $form->check_out_time,
            $form->created_at,
            $form->updated_at,
        ];
    }
}
