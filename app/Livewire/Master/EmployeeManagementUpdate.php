<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Admin;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class EmployeeManagementUpdate extends Component
{
   use WithFileUploads;
        public $name,$designation, $image, $mobile, $email,$id,$employee;
        
        public $designations = [];

        public function mount($id)
        {   
            $this->id = $id;
            $this->designations = Designation::orderBY('name', 'ASC')->get();
            $this->employee = Admin::find($this->id);
            if(!$this->employee){
                abort(404);
            }
            $this->name = $this->employee->name;
            $this->email = $this->employee->email;
            $this->mobile = $this->employee->mobile;
            $this->designation = $this->employee->designation;
            $this->email = $this->employee->email;
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
                'mobile' => 'required|string|max:15|unique:admins,mobile,' . $this->id . '|regex:/^[0-9]{10,15}$/', // Improved mobile validation
                'email' => 'required|email|max:255|unique:admins,email,' . $this->id, // Valid email format
                'image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp', // Increased size to 2MB (2048KB)
            ]);

            DB::beginTransaction();

            try {
                // Handle main image upload
                $imagePath = 'assets/img/profile-image.webp';
                if ($this->image) {
                    $imagePath = storeFileWithCustomName($this->image, 'uploads/Admin');
                }

                // Check if the employee exists
                $store = Admin::find($this->id);
                if (!$store) {
                    throw new \Exception('Employee not found.');
                }

                // Update the Employee
                $store->name = ucwords($this->name);
                $store->designation = $this->designation;
                $store->mobile = $this->mobile;
                $store->email = $this->email;
                if ($this->image) {
                    $store->image = $imagePath; // Update image only if provided
                }
                $store->save();

                DB::commit();

                // Flash a success message and redirect
                session()->flash('message', 'Employee updated successfully!');
                return redirect()->route('admin.employee.list');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error updating user: ' . $e->getMessage());
            }
        }

        public function render()
        {
            return view('livewire.master.employee-management-update');
        }
    }
