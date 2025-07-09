<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AsignedVehicle;
use App\Models\User;
use App\Models\ExchangeVehicle;

class PaymentUserSummary extends Component
{
    public $userId;
    public $user;
    public function mount($user_id){
        $this->userId = $user_id;
        $this->user = User::find($user_id);
        if(!$this->user){
            abort(404);
        }
    }
    public function render()
    {
        // Fetching the assigned vehicle
        $assignedVehicle = AsignedVehicle::whereIn('status', ['assigned','overdue'])
        ->where('user_id', $this->userId)
        ->first();

        $exchangeVehicles  = ExchangeVehicle::with('stock')
        // ->whereIn('status', ['renewal', 'returned'])
        ->where('user_id', $this->userId)->orderBy('id', 'DESC')->paginate(10);

        // Adding assigned vehicle at the start (if it exists)
        if ($assignedVehicle) {
            $assignedVehicle->exchanged_by = $assignedVehicle->assigned_by;
            $exchangeVehicles->getCollection()->prepend($assignedVehicle);
        }
   
        return view('livewire.admin.payment-user-summary',[
            'history'=>$exchangeVehicles ,
        ]);
    }
}
