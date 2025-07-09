<?php
namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Product;
use Livewire\WithFileUploads;
use App\Models\ProductType;
use App\Models\RentalPrice;
use App\Models\ProductImage;
use App\Models\ProductFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Import the Rule class

class UpdateProduct extends Component
{
    use WithFileUploads;

    public $productId,$category_id, $sub_category_id, $title, $short_desc, $long_desc, $image,$product_sku;
    public $meta_title, $meta_keyword, $meta_description,$is_driving_licence_required;
    public $categories = [], $subcategories = [];
    public $is_selling = false;
    public $is_rent = true;
    public $base_price;
    public $display_price;
    public $per_rent_price;
    public $multipleImages = [];
    public $selectedProductTypes = [];
    public $product_type = [];
    public $rental_prices = [];
    public $rent_duration;

    public $existingImages = [];
   
    public $features = [];

    public function mount($productId)
    {
        $product = Product::with('ProductImages')->where('id', $productId)->with('features')->first();
    
        if (!$product) {
            abort(404, 'Product not found.');
        }
    
        $this->productId = $product->id;
        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->title = $product->title;
        $this->product_sku = $product->product_sku;
        $this->short_desc = $product->short_desc;
        $this->is_driving_licence_required = $product->is_driving_licence_required==1?true:false;
        $this->long_desc = $product->long_desc;
        $this->image = $product->image;
        $this->meta_title = $product->meta_title;
        $this->meta_keyword = $product->meta_keyword;
        $this->meta_description = $product->meta_description;
        $this->base_price = $product->base_price;
        $this->display_price = $product->display_price;
        $this->per_rent_price = $product->per_rent_price;
        $this->is_selling = $product->base_price ? true : false;
        $this->categories = Category::all();
        if ($product->category_id) {
            $this->GetSubcat($product->category_id);
        } else {
            $this->subcategories = [];
        }
    
        $this->product_type = ProductType::where('status', 1)
            ->orderBy('title', 'ASC')
            ->get();
    
        // Load product features
        $this->features = $product->features->toArray();
        $this->rental_prices = $product->rentalprice->toArray();
    
        // Load selected product types
        $this->selectedProductTypes = $product->types ? explode(',', $product->types) : [];

        $this->existingImages = $product->ProductImages->pluck('image', 'id')->toArray(); // Map image ID to file path
        // $this->rent_duration = env('DEFAULT_RENT_DURATION', 30);
    }

    public function removeExistingImage($imageId)
    {
        $image = ProductImage::find($imageId);

        if ($image) {
            // Delete the image file from storage
            \Storage::disk('public')->delete($image->image);

            // Delete the record from the database
            $image->delete();

            // Remove from the array of existing images
            unset($this->existingImages[$imageId]);
        }
    }

    public function removeNewImage($index)
    {
        unset($this->multipleImages[$index]);
        $this->multipleImages = array_values($this->multipleImages); // Reindex array
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
    public function removeFeature($index)
    {
        // Check if the feature exists in the database
        if (!empty($this->features[$index]['id'])) {
            // Delete the feature from the database
            ProductFeature::where('id', $this->features[$index]['id'])->delete();
        }

        // Remove the feature from the array
        unset($this->features[$index]);

        // Reindex the array
        $this->features = array_values($this->features);
    }

    
    public function GetSubcat($category_id)
    {
        $this->subcategories = SubCategory::where('category_id', $category_id)->get();
    }

    
    public function updateProduct()
    {
        
            $this->validate([
                'category_id' => 'nullable',
                'sub_category_id' => 'nullable',
                'title' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('products', 'title')->ignore($this->productId)->whereNull('deleted_at'),
                    ],
                    'product_sku' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('products', 'product_sku')->ignore($this->productId)->whereNull('deleted_at'),
                    ],
                // 'short_desc' => 'required|string|max:255',
                // 'long_desc' => 'required|string',
                'image' => $this->image instanceof \Illuminate\Http\UploadedFile ? 'nullable|mimes:jpg,jpeg,png,gif' : 'nullable',
                'base_price' => $this->is_selling ? 'required|numeric' : 'nullable',
                'display_price' => $this->is_selling ? 'required|numeric' : 'nullable',
                // 'per_rent_price' => $this->is_rent ? 'required|numeric' : 'nullable',
                'features.*.title' => 'required|string|max:255', // Validate each feature title
                // 'rental_prices.*.duration' => $this->is_rent ? 'required|numeric' : 'nullable',
                // 'rental_prices.*.duration_type' => $this->is_rent ? 'required|string' : 'nullable',
                // 'rental_prices.*.price' => $this->is_rent ? 'required|numeric' : 'nullable',
            ]);
        DB::beginTransaction();

        try {
            // Handle image upload
            if ($this->image && $this->image instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = storeFileWithCustomName($this->image, 'uploads/product');
                } else {
                    $imagePath = $this->image; // Use the old image if not updated
                }

            // Find the product and update details
             $is_selling_value = $this->is_selling ? 1 : 0;
            $is_rent_value = $this->is_rent ? 1 : 0;
            $product = Product::find($this->productId);
            $selectedProductTypesString = implode(',', $this->selectedProductTypes);
            $product->update([
                'category_id' => $this->category_id,
                'sub_category_id' => $this->sub_category_id,
                'title' => ucwords($this->title),
                'product_sku' => strtoupper($this->product_sku),
                'types' => $selectedProductTypesString,
                'short_desc' => $this->short_desc,
                'long_desc' => $this->long_desc,
                'image' => $imagePath,
                'meta_title' => $this->meta_title,
                'meta_keyword' => $this->meta_keyword,
                'is_driving_licence_required' => $this->is_driving_licence_required==true?1:0,
                'meta_description' => $this->meta_description,
                'is_rent' => $is_rent_value,
                'is_selling' => $is_selling_value,
                'base_price' => $this->base_price,
                'display_price' => $this->display_price,
                // 'per_rent_price' => $this->per_rent_price,
            ]);
            // Update rental_price
            //  foreach ($this->rental_prices as $rental_item) {
            //     if (!empty($rental_item['id'])) {
            //         // Update existing feature
            //         RentalPrice::where('id', $rental_item['id'])->update([
            //            'duration_type' => $rental_item['duration_type'],
            //             'duration' => $rental_item['duration'],
            //             'price' => $rental_item['price'],
            //         ]);
            //     } else {
            //         RentalPrice::create([
            //             'product_id' => $product->id,
            //             'duration_type' => $rental_item['duration_type'],
            //             'duration' => $rental_item['duration'],
            //             'price' => $rental_item['price'],
            //         ]);
            //     }
            // }
            // Update features
            foreach ($this->features as $feature) {
                if (!empty($feature['id'])) {
                    // Update existing feature
                    ProductFeature::where('id', $feature['id'])->update(['title' => $feature['title']]);
                } else {
                    // Add new feature
                    ProductFeature::create([
                        'product_id' => $this->productId,
                        'title' => $feature['title'],
                    ]);
                }
            }

            // Handle multiple image uploads
            foreach ($this->multipleImages as $image) {
                if ($image instanceof \Illuminate\Http\UploadedFile) {
                    // $imagePath = $image->store('uploads/product/multiple', 'public');
                    $imagePath = storeFileWithCustomName($image, 'uploads/product-images');
                    ProductImage::create([
                        'product_id' => $this->productId,
                        'image' => $imagePath,
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Model updated successfully!');
            return redirect()->route('admin.product.index');

        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();
            // dd($e->getMessage());
            // Log the exception for debugging
            // \Log::error('Error uploading CSV data: ' . $e->getMessage());
            session()->flash('error', 'Error uploading CSV data: ' . $e->getMessage());
            return;
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
            $this->per_rent_price = null;
        }
    }

    public function render()
    {
        $this->dispatch('ck_editor_load');
        return view('livewire.product.update-product');
    }
}
