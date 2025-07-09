<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;


class ProductWiseVehicle extends Component
{
    public $vehicles = [];
    public $product_name;
    public $vehicle_number;
    public $vehicle_id;
    public $product_id;
    public $search = "";
    
    public function boot(){
        Paginator::useBootstrap();
    }
    protected $rules = [
        'vehicle_number' => 'required|string|max:255',
    ];

    public function mount($product_id){
        $this->product_id = $product_id;
        $product = Product::find($product_id);
        if (!$product) {
            abort(404); // Return a 404 error page if product is not found
        }
        $this->product_name = $product->title;
    }
    public function searchButtonClicked(){
        $this->mount($this->product_id); // Reset to the first page
    }
    public function resetSearch()
    {
        $this->reset(['search', 'vehicle_number','vehicle_id']);
        $this->mount($this->product_id); // Reset to the first page
    }
    public function save()
    {
        // Dynamically add the unique validation rule when saving
        $rules = $this->rules;
        
        // Add the unique validation rule for title, if updating
        if ($this->vehicle_id) {
            $rules['vehicle_number'] .= '|unique:stocks,vehicle_number,' . $this->vehicle_id;
        } else {
            $rules['vehicle_number'] .= '|unique:stocks,vehicle_number';
        }

        // Validate with the dynamically created rules
        $this->validate($rules);

        // Create or update logic
        if ($this->vehicle_id) {
            $Stock = Stock::findOrFail($this->vehicle_id);
            $Stock->vehicle_number = $this->vehicle_number;
            $Stock->product_id = $this->product_id;

            $Stock->save();
            session()->flash('message', 'Stock updated successfully!');
        } else {
            $Stock = new Stock([
                'product_id' => $this->product_id,
                'vehicle_number' => $this->vehicle_number,
                'status' => true,
            ]);
            
            $Stock->save();
            session()->flash('message', 'Stock created successfully!');
        }

        $this->resetSearch();
    }
    public function deleteVehicle($id)
    {
        $this->dispatch('showConfirm', ['itemId' => $id]);
    }

    public function deleteItem($itemId)
    {
        $stock = Stock::find($itemId);
        if ($stock) {
            $stock->delete();
            $this->mount($this->product_id); // Reset to the first page
            $this->resetSearch();
            session()->flash('success', 'Vehicle deleted successfully!');
        } 
    }
    public function UpdateStatus($id){
        $stock = Stock::where('id', $id)->first();
        if($stock){
            $stock->status = $stock->status==1?0:1;
            $stock->save();
        }else{
            abort(404);
        }
    }

    public function UpdateVehicle($id){
        $stock = Stock::where('id', $id)->first();
        $this->vehicle_number = $stock->vehicle_number;
        $this->vehicle_id = $stock->id;
    }

    public function render()
    {   
        $vehicles = Stock::with('product') // Eager load product details
        ->when($this->search, function ($query) {
            $query->where('vehicle_number', 'like', '%' . $this->search . '%');
        })
        ->when($this->product_id, function ($query) { // Ensure product_id is set
            $query->where('product_id', $this->product_id);
        })
        ->paginate(20);
    
        return view('livewire.product.product-wise-vehicle',[
            'data' => $vehicles
        ]);
    }
}
