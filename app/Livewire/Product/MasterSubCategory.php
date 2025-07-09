<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;


class MasterSubCategory extends Component
{
    use WithPagination;
    public $subCategoryId,$category_id,$title,$categories;

    
    public function mount()
    {
        $this->categories = Category::where('status',1)->orderBy('title','ASC')->get(); // Load categories for dropdown
        
    }

    public function store(){
        $this->validate([
            'category_id' => 'required|exists:categories,id',
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sub_categories')->where(function ($query) {
                        return $query->where('category_id', $this->category_id);
                    }),
                ],
        ],[
            'category_id.required' => 'The category field is mandatory.',
            'category_id.exists' => 'The selected category is invalid.',
            'title.required' => 'The title field cannot be empty.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'A sub-category with this title already exists under the selected category.',
        ]);

        SubCategory::create([
            'title'=>$this->title,
            'category_id'=>$this->category_id
        ]);

        session()->flash('message','SubCategory Created Successfully');
        $this->resetForm();
    }

    public function edit($id){
        $subcategory = SubCategory::findOrFail($id);
        $this->subCategoryId = $subcategory->id;
        $this->category_id = $subcategory->category_id;
        $this->title = $subcategory->title;
    }

    public function update()
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sub_categories')->where(function ($query) {
                        return $query->where('category_id', $this->category_id);
                    })->ignore($this->subCategoryId),
                ],
        ]);

        $subcategory = SubCategory::findOrFail($this->subCategoryId);
        $subcategory->update([
            'category_id' => $this->category_id,
            'title' => $this->title,    
        ]);

        session()->flash('message', 'Subcategory updated successfully!');
        $this->resetForm();
    }

    public function destroy($id)
    {
       $subcategory =  SubCategory::findOrFail($id);
       $subcategory->deleted_at = now();
       $subcategory->save();
       session()->flash('message', 'Subcategory deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $category = SubCategory::findOrFail($id);
        $category->status = !$category->status;  // Toggle the status
        $category->save();  // Save the updated status

        session()->flash('message', 'SubCategory status updated successfully!');
    }

    public function resetForm(){
        $this->category_id = '';
        $this->title = '';
    }

    public function refresh()
    {
        $this->resetForm();
        $this->search = ''; // Reset the search filter
        $subcategories = SubCategory::with('category')->paginate(5);
        // session()->flash('message', 'Data refreshed successfully!');
    }
    public function render()
    {
        $subcategories = SubCategory::with('category')->paginate(5);
        return view('livewire.product.master-sub-category',['subcategories'=>$subcategories]);
    }
}
