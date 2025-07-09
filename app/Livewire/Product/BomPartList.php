<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\BomPart;
use App\Models\Product;
use Livewire\WithFileUploads;

class BomPartList extends Component
{
    use WithFileUploads;
   public $search = '';
    public $bom_parts = [];
    public $products = [];

    public $partId;
    public $part_name;
    public $part_number;
    public $product_id;
    public $part_unit;
    public $part_price;
    public $warranty_in_day;
    public $warranty;
    public $image;
    public $existingImageUrl;

    public $active_tab = 1;


   protected $rules = [
        'part_name' => 'required|string|max:255',
        'part_number' => 'required|string|max:255',
        'product_id' => 'required|integer|exists:products,id', // assuming relation with products table
        'part_unit' => 'required|string|max:100',
        'part_price' => 'required|numeric|min:0',
        'warranty_in_day' => 'required|integer|min:0',
        'warranty' => 'required|in:Yes,No',
        'image' => 'nullable',
    ];

    public function searchButtonClicked()
    {
        $this->mount(); // Reset to the first page
    }
    public function mount()
    {
        $this->products = Product::where('status',1)->get();
        $this->resetErrorBag();
        $this->bom_parts = BomPart::query()
        ->when($this->search, function ($query) {
            $query->where(function ($query) {
                $query->where('part_name', 'like', '%' . $this->search . '%')
                    ->orWhere('part_number', 'like', '%' . $this->search . '%')
                    ->orWhere('product_id', 'like', '%' . $this->search . '%')
                    ->orWhere('part_unit', 'like', '%' . $this->search . '%')
                    ->orWhere('part_price', 'like', '%' . $this->search . '%')
                    ->orWhere('warranty_in_day', 'like', '%' . $this->search . '%')
                    ->orWhere('warranty', 'like', '%' . $this->search . '%')
                    ->orWhere('image', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('id', 'DESC')->get();
    }

   public function newSubmit()
    {
        $this->rules['part_name'] = 'required|string|max:255|unique:bom_parts,part_name';
        $this->validate();
        $imagePath = null;
        if ($this->image && $this->image instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = storeFileWithCustomName($this->image, 'uploads/product');
        }
        BomPart::create([
            'part_name' => $this->part_name,
            'part_number' => $this->part_number,
            'product_id' => $this->product_id,
            'part_unit' => $this->part_unit,
            'part_price' => $this->part_price,
            'warranty_in_day' => $this->warranty_in_day,
            'warranty' => $this->warranty,
            'image' => $imagePath,
        ]);

        session()->flash('success', 'Part created successfully!');
        $this->resetForm();
    }

   public function editPart($id)
    {
        $bomPart = BomPart::findOrFail($id);

        $this->partId = $bomPart->id;
        $this->part_name = $bomPart->part_name;
        $this->part_number = $bomPart->part_number;
        $this->product_id = $bomPart->product_id;
        $this->part_unit = $bomPart->part_unit;
        $this->part_price = $bomPart->part_price;
        $this->warranty_in_day = $bomPart->warranty_in_day;
        $this->warranty = $bomPart->warranty;
        $this->existingImageUrl = $bomPart->image ? asset($bomPart->image) : null;
        
        $this->active_tab = 3;
    }

    public function updatePart()
    {
        $this->rules['part_name'] = 'required|string|max:255|unique:bom_parts,part_name,' . $this->partId;
        $this->validate();

        if($this->image){
            $imagePath = storeFileWithCustomName($this->image, 'uploads/parts');
        }
        
        $bomPart = BomPart::findOrFail($this->partId);
        $bomPart->update([
            'part_name' => $this->part_name,
            'part_number' => $this->part_number,
            'product_id' => $this->product_id,
            'part_unit' => $this->part_unit,
            'part_price' => $this->part_price,
            'warranty_in_day' => $this->warranty_in_day,
            'warranty' => $this->warranty,
            'image' => $this->image?$imagePath:$bomPart->image,
        ]);

        session()->flash('success', 'Part updated successfully!');
        $this->resetForm();
    }


    public function ActiveCreateTab($value)
    {
        $this->resetForm();
        $this->active_tab = $value;
        
    }
    public function deletePart($id)
    {
        try {
            BomPart::findOrFail($id)->delete();
            session()->flash('success', 'Part deleted successfully!');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }


  public function resetForm()
    {
        $this->partId = null;
        $this->search = '';
        $this->part_name = '';
        $this->part_number = '';
        $this->product_id = '';
        $this->part_unit = '';
        $this->part_price = '';
        $this->warranty_in_day = '';
        $this->warranty = '';
        $this->image = '';
        $this->existingImageUrl = '';
        $this->reset(); // Livewire reset helper
        $this->mount(); // Re-initialize component data if needed
    }
    public function resetSearch()
    {
        $this->reset('search'); // Reset the search term
        $this->mount();     // Reset pagination
    }
    public function render()
    {
        return view('livewire.product.bom-part-list', [
            'activeTab' => $this->active_tab,
            'bom_parts' => $this->bom_parts,
        ]);
    }
}
