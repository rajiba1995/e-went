<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class EmployeeManagementCreate extends Component
{
    
        use WithFileUploads;
        public $name,$designation, $image, $mobile, $email;
        
        public $designations = [];

        public function mount()
        {
            $this->designations = Designation::orderBY('name', 'ASC')->get();
        }

        public function GetDesignation($designation_id)
        {
            $this->designation = $designation_id;
        }

        public function saveProduct()
        {
             $this->validate([
                'designation' => 'required|exists:designations,id', // Designation must exist in the designations table
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|max:15|unique:admins,mobile|regex:/^[0-9]{10,15}$/', // Improved mobile validation
                'email' => 'required|email|max:255|unique:admins,email', // Valid email format
                'image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp', // Increased size to 2MB (2048KB)
            ]);

            DB::beginTransaction();

            try {
                // Handle main image upload
                $imagePath = 'assets/img/profile-image.webp';
                if ($this->image) {
                    $imagePath = storeFileWithCustomName($this->image, 'uploads/Admin');
                }

                // Create the Employee
                $store = new Admin;
                $store->name = ucwords($this->name);
                $store->designation = $this->designation;
                $store->mobile = $this->mobile;
                $store->email = $this->email;
                $store->image = $imagePath; // Corrected: Use the $imagePath variable
                $store->password = Hash::make(123456);
                $store->save(); // Save the user (missing in your code)

                DB::commit();

                // Flash a success message and redirect
                session()->flash('message', 'Employee created successfully!');
                return redirect()->route('admin.employee.list');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error creating user: ' . $e->getMessage());
            }
        }

        public function render()
        {
            return view('livewire.master.employee-management-create');
        }
}
