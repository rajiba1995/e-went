<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingActivity;
class OrderDetail extends Component
{
    public $order;
    public $order_id;
    public $user_id;
    public $activities;
    public $status;
    public $customer_total_order = 0;
    public $order_items;
    public function mount($id){
        $this->order_id = $id;
        $this->order = Order::findOrFail($id);
        $this->order_items = OrderItem::where('order_id', $id)->get();
        if (!$this->order) {
            abort(404, 'Order details not found.');
        }
        $this->user_id = $this->order->user_id;
        $this->activities = ShippingActivity::where('order_id',$id)->orderBy("id", "ASC")->get();
        $this->customer_total_order = Order::where('user_id', $this->order->user_id)->count();
    }
    public function updateStatus()
    {
        $this->validate([
            'status' => 'required'
        ]);

        $activity = ShippingActivity::where('order_id', $this->order_id)->orderBy('id','DESC')->first();
        if ($activity) {
            if($activity->status != $this->status){
                $new = New ShippingActivity;
                $new->order_id = $this->order_id;
                $new->user_id = $this->user_id;
                if($this->status==="Ride Canceled"){
                    $new->payment_status = "Refunded";
                    $new->description = "User canceled the ride, refund processed";
                }elseif($this->status==="Vehicle Assigned"){
                    $new->description = "Bike assigned for the ride', 'Yamaha R15 - Black";
                    // $new->vehicle_id = ;
                }elseif($this->status==="Ride Started"){
                    $new->description = "User started the ride', 'Yamaha R15 - Black";
                }elseif($this->status==="Ride Completed"){
                    $new->description = "Ride successfully completed', 'Yamaha R15 - Black";
                }
                $new->status = $this->status;
                $new->save();
            }
            
            session()->flash('status_success', 'Ride status updated successfully!');
        } else {
            session()->flash('status_error', 'Ride not found!');
        }
        $this->mount($this->order_id);
    }
    public function render()
    {
        return view('livewire.admin.order-detail');
    }
}
