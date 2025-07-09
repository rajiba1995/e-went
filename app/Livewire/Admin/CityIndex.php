<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\State;
use App\Models\City;

class CityIndex extends Component
{
    public $cities, $states;
    public $cityId, $name, $state_id, $status, $search;

    // Remove the validation rules from the property and move it to the method
    protected $rules = [
        'name' => 'required|string|max:255',
        'state_id' => 'required',
    ];

    // Mount function to initialize data
    public function mount()
    {
        $this->states = State::where('status', 1)->orderBy('name', 'ASC')->get();
        $this->refresh();
    }

    // Fetch city with search
    public function refresh()
    {
        $this->resetForm();
        $this->cities = City::where('name', 'like', '%' . $this->search . '%')->get();
    }

    // Reset form inputs
    public function resetForm()
    {
        $this->reset(['cityId', 'name', 'state_id', 'status','search']);
    }

    // Create or Update City
    public function save()
    {
        // Dynamically add the unique validation rule when saving
        $rules = $this->rules;
        
        // Add the unique validation rule for title, if updating
        if ($this->cityId) {
            $rules['name'] .= '|unique:cities,name,' . $this->cityId;
        } else {
            $rules['name'] .= '|unique:cities,name';
        }

        // Validate with the dynamically created rules
        $this->validate($rules);

        // Create or update logic
        if ($this->cityId) {
            $city = City::findOrFail($this->cityId);
            $city->name = $this->name;
            $city->state_id = $this->state_id;

            
            $city->save();
            session()->flash('message', 'City updated successfully!');
        } else {
            $city = new City([
                'name' => $this->name,
                'state_id'=>$this->state_id,
                'status' => true,
            ]);
            
            $city->save();
            session()->flash('message', 'City created successfully!');
        }

        $this->resetForm();
        $this->refresh();
    }

    // Edit City
    public function edit($id)
    {
        $city = City::findOrFail($id);

        $this->cityId = $city->id;
        $this->name = $city->name;
        $this->state_id = $city->state_id;
        $this->status = $city->status;
    }

    // Delete city
    public function destroy($id)
    {
        City::findOrFail($id)->delete();

        session()->flash('message', 'City deleted successfully!');
        $this->refresh();
    }

    // Toggle city status
    public function toggleStatus($id)
    {
        $city = City::findOrFail($id);
        $city->status = !$city->status;
        $city->save();

        session()->flash('message', 'City status updated successfully!');
        $this->refresh();
    }
    public function searchButtonClicked()
    {
        $this->cities = City::where('name', 'like', '%' . $this->search . '%')->get();
    }

    public function render()
    {
        return view('livewire.admin.city-index');
    }
}
