<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Validation\Rule;

class VehicleUpdate extends Component
{
    public $models = [];
    public $id, $model,$vehicle_number,$vehicle_track_id,$imei_number,$chassis_number,$friendly_name;
    public function mount($id){
        $this->id = $id;
        $stock = Stock::find($id);
        if (!$stock) {
            abort(404); // This will throw a 404 error page
        }
         // Pre-fill form fields for update
         $this->model = $stock->product_id;
         $this->vehicle_number = $stock->vehicle_number;
         $this->vehicle_track_id = $stock->vehicle_track_id;
         $this->imei_number = $stock->imei_number;
         $this->chassis_number = $stock->chassis_number;
         $this->friendly_name = $stock->friendly_name;
        $this->models = Product::where('status', 1)->orderBy('title', 'ASC')->get();
    }

    public function rules()
    {
        return [
            'model' => ['required', 'exists:products,id'],
            'vehicle_track_id' => ['required', 'string', Rule::unique('stocks', 'vehicle_track_id')->ignore($this->id)],
            'friendly_name' => ['nullable', 'string', 'max:255'],
            'vehicle_number' => ['required', 'string', Rule::unique('stocks', 'vehicle_number')->ignore($this->id)],
            // 'imei_number' => ['required', 'string', Rule::unique('stocks', 'imei_number')->ignore($this->id)],
            'chassis_number' => ['required', 'string', Rule::unique('stocks', 'chassis_number')->ignore($this->id)],
        ];
    }
    public function updateVehicle()
    {
        $validatedData = $this->validate();

        $stock = Stock::findOrFail($this->id);
        $stock->update([
            'product_id' => $validatedData['model'],
            'vehicle_number' => $validatedData['vehicle_number'],
            'vehicle_track_id' => $validatedData['vehicle_track_id'],
            'imei_number' => $this->imei_number,
            'chassis_number' => $validatedData['chassis_number'],
            'friendly_name' => $validatedData['friendly_name'],
        ]);

        session()->flash('message', 'Vehicle updated successfully!');
        return redirect()->route('admin.vehicle.list');
    }
    public function render()
    {
        return view('livewire.product.vehicle-update');
    }
}
