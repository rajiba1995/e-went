<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Gallery;
use App\Models\Product;


class GalleryIndex extends Component
{
    use WithFileUploads;

    public $images = [];
    public $product_id;

    protected $rules = [
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',  // Validate each image
    ];

    protected $messages = [
        'images.required' => 'Please upload at least one image.',
        'images.array' => 'Invalid image format.',
        'images.*.image' => 'Each file must be an image.',
        'images.*.mimes' => 'Images must be in jpg, jpeg, or png format.',
    ];

    public function mount($product_id)
    {
        $this->product_id = $product_id; 
    }

    public function save(){
        $this->validate();
        foreach ($this->images as $image) {
           
            $imgPath = $image->store('uploads/galaries', 'public');
           
            
            Gallery::create([
                'product_id' => $this->product_id,
                'image' => $imgPath,
            ]);
        }   
        
         session()->flash('message', 'Images uploaded successfully.');
    }

    public function destroy($id){
        $galaries = Gallery::find($id);
        if($galaries){
            $imagePath = public_path('storage/'.$galaries->image);

            if(file_exists($imagePath)){
                unlink($imagePath);
            }

            $galaries->delete();
            session()->flash('message','Image deleted successfully');
        }
    }

    public function render()
    {
        $product = Product::select('title')->find($this->product_id);
        $product_name = $product->title;
        $galleries = Gallery::with('product')->where('product_id', $this->product_id)->get();
        return view('livewire.product.gallery-index',['galleries'=>$galleries,'product_name'=>$product_name]);
    }
}
