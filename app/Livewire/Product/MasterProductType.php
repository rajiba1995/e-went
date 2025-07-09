<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\ProductType;

class MasterProductType extends Component
{
    public $productTypeId,$title,$status,$productTypes;
    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255|unique:product_types,title,NULL,id,deleted_at,NULL',
    ];
    public function mount()
    {
        $this->search();
    }

    // Fetch banners with search
    public function searchButtonClicked()
    {
        $this->productTypes = ProductType::where('title', 'like', '%' . $this->search . '%')->get();
    }

    public function save(){
        $this->validate();
        ProductType::create([
            'title'=>$this->title
        ]);

        session()->flash('message', 'Product Type created successfully.');
        $this->resetForm();
        $this->mount();
    }

    public function edit($id){
        $product = ProductType::find($id);
        $this->productTypeId = $product->id;
        $this->title = $product->title;
    }

    public function update()
    {
       
    $this->validate([
        'title' => 'required|string|max:255|unique:product_types,title,' . $this->productTypeId . ',id,deleted_at,NULL',
    ]);

        $product = ProductType::findOrFail($this->productTypeId);

        $product->update([
            'title' => $this->title,
        ]);

        
        session()->flash('message', 'Product Type updated successfully!');
        $this->resetForm();
    }

    public function toggleStatus($id){
        $product = ProductType::findOrFail($id);
        $product->status = !$product->status;
        $product->save();
        session()->flash('message', 'Status updated successfully!');
    }

    public function destroy($id){
        $product = ProductType::findOrFail($id);
        $product->deleted_at = now();
        $product->save();
        session()->flash('message', 'Product Type deleted successfully!');
    }

    public function render()
    {
        // Only fetch filtered results if search is present
        // $productTypesQuery = ProductType::query();
        
        // if ($this->search) {
        //     $productTypesQuery->where('title', 'like', '%' . $this->search . '%');
        // }

        // $this->productTypes = $productTypesQuery->get(); // Get filtered results
        return view('livewire.product.master-product-type',['productTypes'=>$this->productTypes]);
    }


    public function resetForm()
    {
        $this->title = '';
        $this->productTypeId = null;
    }

    // Reset search and reload all product types

    public function search()
    {

        $this->productTypes = ProductType::where('title', 'like', '%' . $this->search . '%')->get();
       
    }
    public function refresh()
    {

        // $this->productTypes = ProductType::where('title', 'like', '%' . $this->search . '%')->get();
        $this->resetForm();
        $this->search = ''; // Reset search filter
        $this->productTypes = ProductType::all(); // Reload all product types
    }

}
