<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Banner;
use Illuminate\Validation\Rule;

class BannerIndex extends Component
{
    use WithFileUploads;

    public $banners; // Stores the filtered list of banners
    public $bannerId, $title, $image, $status, $search;

    // Remove the validation rules from the property and move it to the method
    protected $rules = [
        'title' => 'required|string|max:255',
        'image' => 'nullable|mimes:jpg,jpeg,png,gif',
    ];

    // Mount function to initialize data
    public function mount()
    {
        $this->refresh();
    }

    // Fetch banners with search
    public function refresh()
    {
        $this->resetForm();
        $this->banners = Banner::where('title', 'like', '%' . $this->search . '%')->get();
    }

    // Reset form inputs
    public function resetForm()
    {
        $this->reset(['bannerId', 'title', 'image', 'status']);
    }

    // Create or Update Banner
    public function save()
    {
        // Dynamically add the unique validation rule when saving
        $rules = $this->rules;
        
        // Add the unique validation rule for title, if updating
        if ($this->bannerId) {
            $rules['title'] .= '|unique:banners,title,' . $this->bannerId;
        } else {
            $rules['title'] .= '|unique:banners,title';
            $rules['image'] = 'required|mimes:jpg,jpeg,png,gif';
        }

        // Validate with the dynamically created rules
        $this->validate($rules);

        // Create or update logic
        if ($this->bannerId) {
            $banner = Banner::findOrFail($this->bannerId);
            $banner->title = $this->title;

            // Update image if uploaded
            if ($this->image) {
                // Store the image with a custom name
                $banner->image = storeFileWithCustomName($this->image, 'uploads/banner');
            }

            $banner->save();
            session()->flash('message', 'Banner updated successfully!');
        } else {
            $image_path =null;
            if ($this->image) {
                // Store the image with a custom name
                $image_path = storeFileWithCustomName($this->image, 'uploads/banner');
            }
            $banner = new Banner([
                'title' => $this->title,
                'image' => $image_path,
                'status' => true,
            ]);
            
            $banner->save();
            session()->flash('message', 'Banner created successfully!');
        }

        $this->resetForm();
        $this->refresh();
    }

    // Edit banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        $this->bannerId = $banner->id;
        $this->title = $banner->title;
        $this->status = $banner->status;
    }

    // Delete banner
    public function destroy($id)
    {
        Banner::findOrFail($id)->delete();

        session()->flash('message', 'Banner deleted successfully!');
        $this->refresh();
    }

    // Toggle banner status
    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = !$banner->status;
        $banner->save();

        session()->flash('message', 'Banner status updated successfully!');
        $this->refresh();
    }

    public function render()
    {
        return view('livewire.master.banner-index');
    }
}
