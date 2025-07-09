<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\RentalPrice;
use App\Models\ProductFeature;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use Illuminate\Validation\Rule;



class AddProduct extends Component
{
    use WithFileUploads;
    public $productId,$category_id, $sub_category_id, $title, $short_desc, $long_desc, $image, $product_sku;
    public $meta_title, $meta_keyword, $meta_description;
    public $is_driving_licence_required = true;
    public $categories = [], $subcategories = [], $product_type = [];

    public $errorMessage = [];
    public $is_selling = true;
    public $is_rent = true;
    public $base_price;
    public $display_price;
    public $per_hr_rent;
    public $per_rent_price;

    public $features = [], $selectedProductTypes = [], $rental_prices = [];
    public $feature_title = '';

    public $rent_duration;
    public $multipleImages = [];

    public function mount()
    {
        $this->categories = Category::where('status',1)->orderBY('title', 'ASC')->get();
        $this->product_type = ProductType::where('status',1)->orderBY('title', 'ASC')->get();
        // $this->rent_duration = env('DEFAULT_RENT_DURATION', 30); // Default to 30 if not set
    }

    public function GetSubcat($category_id)
    {
        $this->subcategories = SubCategory::where('category_id', $category_id)->get();
    }

    public function saveProduct()
    {
        
        $this->reset(['errorMessage']);
        $this->errorMessage = array();
        // Validate customer
        if ($this->is_rent && (!is_array($this->rental_prices) || count($this->rental_prices) === 0)) {
            $this->errorMessage['error_rental_prices'] = 'Please enter at least one rental price.';
        }
        // Validate the input
        $this->validate([
            'category_id' => 'nullable',
            'sub_category_id' => 'nullable',
             'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'title')->whereNull('deleted_at'),
            ],
            'product_sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_sku')->whereNull('deleted_at'),
            ],
            'short_desc' => 'nullable|string',
            'long_desc' => 'nullable|string',
            'image' => 'nullable|image|max:2024|mimes:jpg,jpeg,png,webp', // 2MB max image size
            'multipleImages.*' => 'nullable|image|max:2024|mimes:jpg,jpeg,png,webp', // Validation for multiple images
            'base_price' => $this->is_selling ? 'required|numeric' : 'nullable',
            'display_price' => $this->is_selling ? 'required|numeric' : 'nullable',
            // 'per_rent_price' => $this->is_rent ? 'required|numeric' : 'nullable',
            'features.*.title' => 'required|string|max:255',
            // 'rental_prices.*.duration' => $this->is_rent ? 'required|numeric' : 'nullable',
            // 'rental_prices.*.duration_type' => $this->is_rent ? 'required|string' : 'nullable',
            // 'rental_prices.*.price' => $this->is_rent ? 'required|numeric' : 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Handle main image upload
            $imagePath = null;
            if ($this->image) {
                $imagePath = storeFileWithCustomName($this->image, 'uploads/product');
            } else {
                $imagePath = 'assets/img/default-product.webp';
            }

            // Set selling and rent values
            $is_selling_value = $this->is_selling ? 1 : 0;
            $is_rent_value = $this->is_rent ? 1 : 0;

            // Create the product
            $selectedProductTypesString = implode(',', $this->selectedProductTypes);
            $product = Product::create([
                'category_id' => $this->category_id,
                'sub_category_id' => $this->sub_category_id,
                'title' => ucwords($this->title),
                'product_sku' => strtoupper($this->product_sku),
                'types' => $selectedProductTypesString,
                'is_driving_licence_required' => $this->is_driving_licence_required,
                'short_desc' => $this->short_desc,
                'long_desc' => $this->long_desc,
                'image' => $imagePath,
                'meta_title' => $this->meta_title,
                'meta_keyword' => $this->meta_keyword,
                'meta_description' => $this->meta_description,
                'base_price' => $this->base_price,
                'display_price' => $this->display_price,
                // 'per_rent_price' => $this->per_rent_price,
                'is_selling' => $is_selling_value,
                'is_rent' => $is_rent_value,
                // 'rent_duration' => $this->rent_duration,
            ]);

            // Insert related Product Features
            foreach ($this->features as $feature) {
                ProductFeature::create([
                    'product_id' => $product->id,
                    'title' => $feature['title'],
                ]);
            }
            // if(count($this->rental_prices)>0){
            //     foreach ($this->rental_prices as $rental_item) {
            //         RentalPrice::create([
            //             'product_id' => $product->id,
            //             'duration_type' => $rental_item['duration_type'],
            //             'duration' => $rental_item['duration'],
            //             'price' => $rental_item['price'],
            //         ]);
            //     }
            // }
            // Handle multiple images upload
            if ($this->multipleImages) {
                foreach ($this->multipleImages as $image) {
                    $imagePath = storeFileWithCustomName($image, 'uploads/product');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Flash a success message and redirect
            session()->flash('message', 'Model created successfully!');
            return redirect()->route('admin.product.index');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            // Rollback transaction in case of an error
            DB::rollBack();
            \Log::error('Product creation failed', ['error' => $e->getMessage()]);
            session()->flash('error', $e->getMessage());
        }
    }


    public function toggleSellingFields(){
         if (!$this->is_selling) {
        $this->base_price = null;
        $this->display_price = null;
    }
    }

    public function toggleRentFields(){
        if (!$this->is_rent) {
            $this->per_hr_rent = null;
        }
    }
     // Method to add a feature
     public function addRentalProce()
     {
         $this->rental_prices[] = ['duration' => '', 'duration_type'=>'','price'=>''];  // Add an empty feature
     }
     // Method to remove a feature
    public function removeRentalProce($index)
    {
        unset($this->rental_prices[$index]);
        $this->rental_prices = array_values($this->rental_prices);  // Re-index the array
    }
     public function addFeature()
     {
         $this->features[] = ['title' => ''];  // Add an empty feature
     }
     // Method to remove a feature
    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);  // Re-index the array
    }

    public function render()
    {
        $this->dispatch('ck_editor_load');
        return view('livewire.product.add-product');
    }
}