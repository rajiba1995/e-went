<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Category;

class MasterCategory extends Component
{
    public $categoryId,$title,$status,$categories;
    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255|unique:categories,title,NULL,id,deleted_at,NULL',
    ];

    public function save(){
        $this->validate();
        Category::create([
            'title'=>$this->title
        ]);

        session()->flash('message', 'Category created successfully.');
        $this->resetForm();
    }

    public function edit($id){
        $category = Category::find($id);
        $this->categoryId = $category->id;
        $this->title = $category->title;
    }

    public function update()
    {
       
    $this->validate([
        'title' => 'required|string|max:255|unique:categories,title,' . $this->categoryId . ',id,deleted_at,NULL',
    ]);

        $category = Category::findOrFail($this->categoryId);

        $category->update([
            'title' => $this->title,
        ]);

        
        session()->flash('message', 'Category updated successfully!');
        $this->resetForm();
    }

    public function toggleStatus($id){
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();
        session()->flash('message', 'Status updated successfully!');
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->deleted_at = now();
        $category->save();
        session()->flash('message', 'Category deleted successfully!');
    }

    public function render()
    {
        $this->categories = Category::where('title', 'like', '%' . $this->search . '%')->get();
        return view('livewire.product.master-category');
    }

    public function resetForm(){
        $this->title = '';
        $this->categoryId = null;
    }
    public function refresh()
    {
        $this->resetForm();
        $this->search = ''; // Reset the search filter
        $this->categories = Category::all(); // Reload the categories
        // session()->flash('message', 'Data refreshed successfully!');
    }

}
