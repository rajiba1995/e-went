<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\AsignedVehicle;
use App\Models\ExchangeVehicle;

class UserRideSummaryExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $rider_id;
      public function __construct($rider_id)
    {
        $this->rider_id = $rider_id;
    }
    public function collection()
    {
        // Fetching the assigned vehicle
        $assignedVehicle = AsignedVehicle::whereIn('status', ['assigned','overdue'])
            ->where('user_id', $this->rider_id)
            ->first();

        // Fetching exchange vehicles
        $exchangeVehicles = ExchangeVehicle::with('stock', 'admin', 'order')
            ->where('user_id', $this->rider_id)
            ->orderBy('id', 'DESC')
            ->get();

        // Adding assigned vehicle at the start (if it exists)
        if ($assignedVehicle) {
            $assignedVehicle->exchanged_by = $assignedVehicle->assigned_by;
            $exchangeVehicles->prepend($assignedVehicle); // Fixed prepend
        }

        // Transforming data
        $transformedData = $exchangeVehicles->map(function ($data) {
            return [
                'models' => $data->stock && $data->stock->product ? $data->stock->product->title : "N/A",
                'vehicle' => $data->stock ? $data->stock->vehicle_number : "N/A",
                'start_date' => $data->start_date ? $data->start_date : 'N/A',
                'end_date' => $data->end_date ? $data->end_date : 'N/A',
                'rent_amount' => $data->order ? $data->order->rental_amount : '0.00',
                'rent_status' => ucwords($data->status),
                'action_by' => $data->admin ? $data->admin->email : '....',
            ];
        });

        // dd($transformedData->toArray()); // Debugging

        return $transformedData;
    }


    public function headings(): array
    {
        return [
            'Models',
            'Vehicle Number',
            'Start Date',
            'End Date',
            'Rent Amount',
            'Rent Status',
            'Action By',
        ];
    }
}
