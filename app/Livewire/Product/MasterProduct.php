<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Product;
use App\Models\RentalPrice;
use Livewire\WithFileUploads;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination; // Import WithPagination trait

class MasterProduct extends Component
{
    use WithFileUploads, WithPagination; // Include WithPagination trait

    public $search = '';

    public function boot()
    {
        Paginator::useBootstrap();
    }
    public function searchButtonClicked()
    {
        $this->resetPage(); // Reset to the first page
    }

    public function DeleteItem($id){
        $this->dispatch('showConfirm', ['itemId' => $id]);
    }
    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }
        $product->deleted_at = now();
        $product->save();
        session()->flash('message', 'Product deleted successfully.');
    }

    public function toggleStatus($productId)
    {
        $product = Product::findOrFail($productId);
        $product->status = !$product->status;
        $product->stock = !$product->stock;
        $product->save();
        session()->flash('message', 'Product status updated successfully.');
    }

    public function toggleStockStatus($productId)
    {
        $product = Product::findOrFail($productId);
        $product->stock = $product->stock == 1 ? 0 : 1;
        $product->save();
        session()->flash('message', 'Product stock updated successfully.');
    }

    public function setAsFeatured($productId)
    {
        $product = Product::find($productId);
        $product->update(['is_featured' => $product->is_featured == 0 ? 1 : 0]);
        session()->flash('message', 'Product marked as Featured.');
    }

    public function setAsNewArrival($productId)
    {
        $product = Product::find($productId);
        $product->update(['is_new_arrival' => $product->is_new_arrival == 0 ? 1 : 0]);
        session()->flash('message', 'Product marked as New Arrival');
    }

    public function setAsBestseller($productId)
    {
        $product = Product::find($productId);
        $product->update(['is_bestseller' => $product->is_bestseller == 0 ? 1 : 0]);
        session()->flash('message', 'Product marked as Bestseller');
    }

    public function resetSearch()
    {
        $this->reset('search'); // Reset the search term
        $this->resetPage();     // Reset pagination
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('product_sku', 'like', '%' . $this->search . '%')
                        ->orWhere('types', 'like', '%' . $this->search . '%')
                        ->orWhere('short_desc', 'like', '%' . $this->search . '%')
                        ->orWhere('long_desc', 'like', '%' . $this->search . '%')
                        ->orWhere('base_price', 'like', '%' . $this->search . '%')
                        ->orWhere('display_price', 'like', '%' . $this->search . '%');
                });
            })
            ->with(['category', 'subcategory',
                'rentalprice' => function ($query) {
                $query->orderBy('duration', 'ASC'); // Order rental prices directly in the query
            }])
            ->paginate(20);

        return view('livewire.product.master-product', [
            'products' => $products
        ]);
    }
}
