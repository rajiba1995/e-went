<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentSummaryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data, $startDate, $endDate;

    public function __construct($data, $startDate, $endDate)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    public function collection()
    {
       $exportData = [];

        foreach ($this->data as $item) {
            // Parent Model Row
            $exportData[] = [
                'Model Title' => $item['title'],
                'Model Deposit Amount' => $item['deposit_amount'],
                'Model Rental Amount' => $item['rental_amount'],
                'Model Total Amount' => $item['total_amount'],
                'Vehicle Number' => '',
                'Vehicle Deposit Amount' => '',
                'Vehicle Rental Amount' => '',
                'Vehicle Total Amount' => ''
            ];

            // Vehicle Rows
            foreach ($item['vehicles'] as $vehicle) {
                $exportData[] = [
                    'Model Title' => '',
                    'Model Deposit Amount' => '',
                    'Model Rental Amount' => '',
                    'Model Total Amount' => '',
                    'Vehicle Number' => $vehicle['vehicle_number'],
                    'Vehicle Deposit Amount' => $vehicle['deposit_amount'],
                    'Vehicle Rental Amount' => $vehicle['rental_amount'],
                    'Vehicle Total Amount' => $vehicle['total_amount']
                ];
            }
        }

        return collect($exportData);
    }
    public function headings(): array
    {
        return [
            'Model Title',
            'Model Deposit Amount',
            'Model Rental Amount',
            'Model Total Amount',
            'Vehicle Number',
            'Vehicle Deposit Amount',
            'Vehicle Rental Amount',
            'Vehicle Total Amount'
        ];
    }
}
