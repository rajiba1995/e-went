<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;

class Dashboard extends Component
{
    public $data;
    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function mount(){
      // Step 2: Fetch vehicle data using the token
        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/';

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $vehiclesData = json_decode($vehiclesResponse, true);

        if (!$vehiclesData) {
            $this->data = ['error' => 'Failed to fetch data'];
        } else {
            $this->data = $vehiclesData;
        }
        // dd($this->data);
    }

    public function render()
    {
        $all_vehicles = Stock::all();

        $assigned_vehicles = Stock::with('assignedVehicle')
        ->whereHas('assignedVehicle')->get();

        // $unassigned_vehicles = Stock::whereDoesntHave('assignedVehicle', function ($query) {
        //     $query->whereIn('status1', ['assigned','sold']); // Ensure it's truly unassigned
        // })->get();

        $unassigned_vehicles = Stock::whereDoesntHave('assignedVehicle', function ($query) {
            $query->whereIn('status', ['assigned','sold']); // Ensure it's truly unassigned
        })->whereDoesntHave('overdueVehicle', function ($query) {
            $query->whereIn('status', ['overdue']); // Ensure it's truly unassigned
        })

        ->orderBy('id', 'DESC')->get()->count();
       $overdue_vehicles = Stock::with('overdueVehicle')
        ->whereHas('overdueVehicle') // Ensures only assigned vehicles are fetched

        ->orderBy('id', 'DESC')->get()->count();

        $total = count($all_vehicles);
        $assigned = count($assigned_vehicles);
        $unassigned = $unassigned_vehicles;
        $overdue = ($overdue_vehicles);

        $assigned_percent = $total > 0 ? round(($assigned / $total) * 100) : 0;
        $unassigned_percent = $total > 0 ? round(($unassigned / $total) * 100) : 0;
        $overdue_percent = $total > 0 ? round(($overdue / $total) * 100) : 0;

        $admin = Auth::guard('admin')->user();
        return view('livewire.admin.dashboard', [
            'data' => $this->data,
            'all_vehicles' => $total,
            'assigned_vehicles' => $assigned,
            'unassigned_vehicles' => $unassigned,
            'overdue_vehicles' => $overdue,
            'assigned_percent' => $assigned_percent,
            'unassigned_percent' => $unassigned_percent,
            'overdue_percent' => $overdue_percent,
            'admin' => $admin
        ]);
    }
}
