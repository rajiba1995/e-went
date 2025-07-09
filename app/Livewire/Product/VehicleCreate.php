<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Validation\Rule;

class VehicleCreate extends Component
{
    public $existing_stock = [];
    public $models = [];
    public $vehicles = [];
    public $vehicle_mapping = [];
    public $model,$vehicle_number,$vehicle_track_id,$imei_number,$chassis_number,$friendly_name;
    public function mount(){

        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles?perPage=99';

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $vehiclesData = json_decode($vehiclesResponse, true);
        if($vehiclesData){
            if($vehiclesData['success']==true){
                $this->vehicles = $vehiclesData['data']['vehicles'];
                foreach ($this->vehicles as $vehicle) {
                    $this->vehicle_mapping[$vehicle['number']] = $vehicle['vehicleUuid'];
                }
            }elseif($vehiclesData['success']==false){
                session()->flash('error', $vehiclesData['data']['errors'][0]['message']);
            }
        }
        
        $this->models = Product::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->existing_stock = Stock::orderBy('vehicle_number', 'ASC')->get()->pluck('vehicle_number')->toArray();
       
    }
    public function selectVehicle($number)
    {
        $vehicle_track_id = $this->vehicle_mapping[$number] ?? null;
        if ($vehicle_track_id) {
            $this->vehicle_track_id = $vehicle_track_id;
        }

        $vehiclesUrl = 'https://app.loconav.sensorise.net/integration/api/v1/vehicles/'.$vehicle_track_id;

        $ch = curl_init($vehiclesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Authentication: " . env('LOCONAV_TOKEN'),
            "Accept: application/json"
        ]);

        $vehiclesResponse = curl_exec($ch);
        curl_close($ch);

        $vehiclesData = json_decode($vehiclesResponse, true);

        if($vehiclesData['success']==true){
            $this->chassis_number = $vehiclesData['data']['chassisNumber'];
        }elseif($vehiclesData['success']==false){
            session()->flash('error', $vehiclesData['data']['errors'][0]['message']);
        }
    }

    protected $rules = [
        'model' => 'required|exists:products,id',
        'vehicle_track_id' => 'required|string|unique:stocks,vehicle_track_id',
        'friendly_name' => 'nullable|string|max:255',
        'vehicle_number' => 'required|string|unique:stocks,vehicle_number',
        'imei_number' => 'nullable|string|unique:stocks,imei_number',
        'chassis_number' => 'required|string|unique:stocks,chassis_number',
    ];
    public function saveVehicle()
    {
        $validatedData = $this->validate();
        Stock::create([
            'product_id' => $validatedData['model'],
            'vehicle_number' => $validatedData['vehicle_number'],
            'vehicle_track_id' => $validatedData['vehicle_track_id'],
            'imei_number' => $validatedData['imei_number'],
            'chassis_number' => $validatedData['chassis_number'],
            'friendly_name' => $validatedData['friendly_name'],
        ]);

        session()->flash('message', 'Vehicle created successfully!');
        return redirect()->route('admin.vehicle.list');
    }
    public function render()
    {
        return view('livewire.product.vehicle-create');
    }
}
