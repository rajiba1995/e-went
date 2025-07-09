<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\RentalPrice;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class MasterSubscription extends Component
{
    use WithPagination;
    public $subscriptionId = null;
    public $asset = null;
    public $model,$models,$subscription_type,$duration,$deposit_amount,$rental_amount;

    
    protected function rules()
    {
        return [
            'model' => 'required|exists:products,id',
            'subscription_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rental_prices')->where(function ($query) {
                    return $query->where('product_id', $this->model);
                }),
            ],
            'duration' => 'required|integer|min:1',
            'deposit_amount' => 'required|numeric|min:0',
            'rental_amount' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'model.required' => 'The model field is mandatory.',
        'model.exists' => 'The selected model is invalid.',
        'subscription_type.required' => 'The subscription type is required.',
        'duration.required' => 'The duration is required.',
        'deposit_amount.required' => 'The deposit amount is required.',
        'rental_amount.required' => 'The rental amount is required.',
    ];
    public function mount()
    {
        $this->models = Product::where('status',1)->orderBy('title','ASC')->get(); // Load categories for dropdown
    }

    public function filter($value){
        $this->asset = $value;
    }

    public function GetDuration($duration)
    {
        $this->duration = $duration;
    }
    public function store(){
        $this->validate();
         // Store new subscription
         RentalPrice::create([
            'product_id' => $this->model,
            'subscription_type' => $this->subscription_type,
            'duration' => $this->duration,
            'deposit_amount' => $this->deposit_amount,
            'rental_amount' => $this->rental_amount,
        ]);
        
        session()->flash('message', 'Subscription created successfully!');
        $this->refresh();
    }

    public function edit($id){
        $subscription = RentalPrice::findOrFail($id);
        $this->subscriptionId = $subscription->id;
        $this->model = $subscription->product_id;
        $this->subscription_type = $subscription->subscription_type;
        $this->duration = $subscription->duration;
        $this->deposit_amount = $subscription->deposit_amount;
        $this->rental_amount = $subscription->rental_amount;
    }

    public function update()
    {
        $this->validate([
            'model' => 'required|exists:products,id',
            'subscription_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rental_prices')->where(function ($query) {
                    return $query->where('product_id', $this->model);
                })->ignore($this->subscriptionId), // Ignore current record when updating
            ],
            'duration' => 'required|integer|min:1',
            'deposit_amount' => 'required|numeric|min:0',
            'rental_amount' => 'required|numeric|min:0',
        ], [
            'subscription_type.unique' => 'This subscription type already exists.',
        ]);
    
        // Find and update the subscription
        $subscription = RentalPrice::findOrFail($this->subscriptionId);
        $subscription->update([
            'product_id' => $this->model,
            'subscription_type' => $this->subscription_type,
            'duration' => $this->duration,
            'deposit_amount' => $this->deposit_amount,
            'rental_amount' => $this->rental_amount,
        ]);
    
        session()->flash('message', 'Subscription updated successfully!');
        $this->refresh();
    }

    public function toggleStatus($id)
    {
        $subscription = RentalPrice::findOrFail($id);
        $subscription->status = !$subscription->status;  // Toggle the status
        $subscription->save();  // Save the updated status

        session()->flash('message', 'Subscription status updated successfully!');
    }
    public function destroy($id)
    {
       $subscription =  RentalPrice::findOrFail($id);
       $subscription->delete();
       session()->flash('message', 'Subscription deleted successfully!');
    }


    public function refresh()
    {
        $this->reset(['model', 'subscription_type', 'duration','deposit_amount','rental_amount', 'subscriptionId', 'asset']);
        $this->search = ''; // Reset the search filter
    }
    public function render()
    {
        $query = RentalPrice::with('product');

        if ($this->asset) {
            $query->where('product_id', $this->asset);
        }
    
        $subscriptions = $query->get();
        return view('livewire.product.master-subscription', ['subscriptions'=>$subscriptions]);
    }
}
