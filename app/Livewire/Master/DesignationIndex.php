<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Designation;
use App\Models\Admin;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;


class DesignationIndex extends Component
{
    use WithPagination;
    public $designationId, $name, $status, $search;

    // Remove the validation rules from the property and move it to the method
    protected $rules = [
        'name' => 'required|string|max:255',
    ];


    // Reset form inputs
    public function resetForm()
    {
        $this->reset(['designationId', 'name', 'status','search']);
    }

    // Create or Update Banner
    public function save()
    {
        // Dynamically add the unique validation rule when saving
        $rules = $this->rules;
        
        // Add the unique validation rule for name, if updating
        if ($this->designationId) {
            $rules['name'] .= '|unique:designations,name,' . $this->designationId;
        } else {
            $rules['name'] .= '|unique:designations,name';
        }

        // Validate with the dynamically created rules
        $this->validate($rules);

        // Create or update logic
        if ($this->designationId) {
            $designation = Designation::findOrFail($this->designationId);
            $designation->name = $this->name;

            $designation->save();
            session()->flash('message', 'Designation updated successfully!');
            $this->resetForm();
        } else {
            $designation = new Designation([
                'name' => $this->name,
            ]);
            
            $designation->save();
            session()->flash('message', 'Designation created successfully!');
        }
    }
    public function deleteDesignationWarning($id){
        $this->dispatch('updateDesignationData', ['itemId' => $id]);
    }
    public function DesignationAssignedCheck(){
        $this->dispatch('alertForDesabledItem');
    }

    public function destroy($id)
    {
        Designation::findOrFail($id)->delete();
        session()->flash('message', 'Designation deleted successfully!');
    }

    public function isDesignationAssigned($designationId)
    {
        return Admin::where('designation', $designationId)->exists();
    }


    // Edit Designation
    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $this->designationId = $designation->id;
        $this->name = $designation->name;
        $this->status = $designation->status;

    }

    // Delete Designation
    

    public function btn_search(){
    }
    // Toggle Designation status
    public function toggleStatus($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->status = !$designation->status;
        $designation->save();

        session()->flash('message', 'Designation status updated successfully!');
    }

    public function render()
    {
         $designations = Designation::where('name', 'like', '%' . $this->search . '%')->orderBy('name', 'ASC')->paginate(10);
        return view('livewire.master.designation-index',[
            'designations'=>$designations,
        ]);
    }
}
