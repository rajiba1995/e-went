<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Stock;
use App\Models\Product;
use App\Models\PaymentItem;
use App\Models\AsignedVehicle;
use App\Models\User;
use App\Models\ExchangeVehicle;

class PaymentVehicleSummary extends Component
{
    public $models = [];
    public $model,$vehicle,$model_id,$vehicle_id,$start_date,$end_date;
    public function mount($model_id = null,$vehicle_id = null){
        
        if($model_id){
            $this->model =Product::find($model_id);
            if(!$this->model){
                $this->model_id = $model_id;
                abort(404);
            } 
        }
        if($vehicle_id){
            $this->vehicle =Stock::find($vehicle_id);
            if(!$this->vehicle){
                $this->vehicle_id = $vehicle_id;
                abort(404);
            } 
        }
        
        $this->models = Product::where('status', 1)->orderBy('title', 'ASC')->get();
    }

    public function FilterModel($value){
        $this->resetPageField();
        $this->model =Product::find($value);
        $this->model_id =$value;
    }
    public function resetPageField(){
        $this->reset(['vehicle_id','model_id','model','vehicle']);
    }
    public function render()
    {
        // Fetching the assigned vehicle
        $assignedVehicle = AsignedVehicle::where('status', 'assigned')
        ->when($this->vehicle_id, function ($query) {
            return $query->where('vehicle_id', $this->vehicle_id);
        })
        ->when($this->model_id, function ($query) {
            return $query->whereHas('order', function ($q) {
                $q->where('product_id', $this->model_id);
            });
        })
        ->first();

        $exchangeVehicles = ExchangeVehicle::with('stock')
        ->when($this->vehicle_id, function ($query) {
            $query->where('vehicle_id', $this->vehicle_id);
        })
        ->when($this->model_id, function ($query) {
            $query->whereHas('order', function ($q) {
                $q->where('product_id', $this->model_id);
            });
        })->whereIn('status', ['returned','renewal'])
        ->orderBy('id', 'DESC')
        ->paginate(10);


        // Adding assigned vehicle at the start (if it exists)
        if ($assignedVehicle) {
            $assignedVehicle->exchanged_by = $assignedVehicle->assigned_by;
            $exchangeVehicles->getCollection()->prepend($assignedVehicle);
        }

        return view('livewire.admin.payment-vehicle-summary',[
            'history'=>$exchangeVehicles ,
        ]);
    }
}
