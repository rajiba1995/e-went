<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Stock;

class VehiclePaymentSummary extends Component
{
    public $vehicle;
    public function mount($vehicle_id){
        $this->vehicle =Stock::find($vehicle_id);
        if(!$this->vehicle){
            abort(404);
        }

        
    }
    public function render()
    {
        return view('livewire.product.vehicle-payment-summary');
    }
}
