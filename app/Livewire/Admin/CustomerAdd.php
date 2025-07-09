<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerAdd extends Component
{
    use WithFileUploads;
    public $customer_id, $name, $email, $mobile, $address, $city, $pincode;
    public $image, $driving_licence, $govt_id_card, $cancelled_cheque, $current_address_proof;


    public function mount(){
        $this->customer_id = $this->generateCustomerId();
    }
    public function generateCustomerId()
    {
        do {
            // Generate a new customer ID
            $lastCustomer = User::orderBy('id', 'desc')->first();
            $customerId = 'EW-' . str_pad($lastCustomer ? $lastCustomer->id + 1 : 1, 8, '0', STR_PAD_LEFT);
    
            // Check if the generated ID already exists in the database
            $exists = User::where('customer_id', $customerId)->exists();
    
        } while ($exists);
    
        return $customerId;
    }
    
    public function saveProduct()
    {
        // Validation rules
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'pincode' => 'nullable|digits:6',
            'image' => 'nullable|image|max:1024', // Max 1MB
            'driving_licence' => 'nullable|file|max:2048', // Max 2MB
            'govt_id_card' => 'nullable|file|max:2048', // Max 2MB
            'cancelled_cheque' => 'nullable|file|max:2048', // Max 2MB
            'current_address_proof' => 'nullable|file|max:2048', // Max 2MB
        ]);

        // Save the files to storage if they exist
        $imagePath = $this->image ? storeFileWithCustomName($this->image, 'uploads/user') : null;
        $drivingLicensePath = $this->driving_licence ? storeFileWithCustomName($this->driving_licence, 'uploads/driving_licences') : null;
        $govtIdPath = $this->govt_id_card ? storeFileWithCustomName($this->govt_id_card, 'uploads/govt_id_cards') : null;
        $cancelledChequePath = $this->cancelled_cheque ? storeFileWithCustomName($this->cancelled_cheque, 'uploads/cancelled_cheque') : null;
        $addressProofPath = $this->current_address_proof ? storeFileWithCustomName($this->current_address_proof, 'uploads/current_address_proof') : null;


        // Save customer details in the database
        User::create([
            'customer_id' => $this->customer_id,
            'name' => ucwords($this->name),
            'email' => $this->email,
            'mobile' => $this->mobile,
            'password' => Hash::make(123456),
            'address' => $this->address,
            'city' => $this->city,
            'pincode' => $this->pincode,
            'profile_image' => $imagePath,
            'driving_licence' => $drivingLicensePath,
            'govt_id_card' => $govtIdPath,
            'cancelled_cheque' => $cancelledChequePath,
            'current_address_proof' => $addressProofPath,
        ]);

        // Flash success message and reset fields
        session()->flash('message', 'Customer added successfully!');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.admin.customer-add');
    }
}
