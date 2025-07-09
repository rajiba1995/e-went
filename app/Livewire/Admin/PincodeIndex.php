<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\City;
use Livewire\WithFileUploads;
use Livewire\WithPagination; // Import WithPagination trait
use Illuminate\Support\Facades\Storage;
use App\Models\Pincode;
use Illuminate\Support\Facades\DB;

class PincodeIndex extends Component
{
    use WithFileUploads, WithPagination; // Include WithPagination trait

    public $pincodes, $cities;
    public $pinId, $pincode, $city_id, $status, $search;
    public $modal_activity_class = 0;
    public $csvFile;

    protected $rules =[
        'pincode'=>'required|string|max:255',
        'city_id'=>'required',
    ];

    public function mount(){
        $this->cities = City::where('status', 1)->orderBy('name', 'ASC')->get();
    }

     // Fetch city with search
     public function refresh()
     {
         $this->resetForm();
     }

     public function resetForm(){
        $this->reset(['pinId', 'pincode', 'city_id', 'status','search']);
    }

     // Create or Update City
     public function save()
     {
         // Dynamically add the unique validation rule when saving
         $rules = $this->rules;
         
         // Add the unique validation rule for title, if updating
         if ($this->pinId) {
             $rules['pincode'] .= '|unique:pincodes,pincode,' . $this->pinId;
         } else {
             $rules['pincode'] .= '|unique:pincodes,pincode';
         }
 
         // Validate with the dynamically created rules
         $this->validate($rules);
 
         // Create or update logic
         if ($this->pinId) {
             $pcode = Pincode::findOrFail($this->pinId);
             $pcode->pincode = $this->pincode;
             $pcode->city_id = $this->city_id;
 
             
             $pcode->save();
             session()->flash('message', 'Pincode updated successfully!');
         } else {
             $pcode = new Pincode([
                 'pincode' => $this->pincode,
                 'city_id'=>$this->city_id,
                 'status' => true,
             ]);
             
             $pcode->save();
             session()->flash('message', 'Pincode created successfully!');
         }
 
         $this->resetForm();
         $this->refresh();
     }
 
     // Edit City
     public function edit($id)
     {
         $pcode = Pincode::findOrFail($id);
         $this->pinId = $pcode->id;
         $this->pincode = $pcode->pincode;
         $this->city_id = $pcode->city_id;
         $this->status = $pcode->status;
     }
 

    public function searchButtonClicked()
    {
        $this->mount(); // Reset to the first page
    }


    public function uploadFile()
    {
        // Validate the uploaded file
        $this->validate([
            'csvFile' => 'required|mimes:csv,txt|max:5120', // 5 MB limit
        ]);
        DB::beginTransaction();

        try {
          // Get the uploaded file
        $file = $this->csvFile;
            // Generate a unique name for the file
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        // Store the file in storage folder
        $filePath = $file->storeAs('public/csv/pincode', $fileName);

        // Now you can get the file's URL using storage URL helper
        $fileUrl = Storage::url($filePath);  // This will give you the public URL
      
        // Parse the CSV file
        $csvData = array_map('str_getcsv', file(storage_path('app/' . $filePath)));
        // Remove the header (first row) and get unique rows based on index[0]
        $csvDataWithoutHeader = array_slice($csvData, 1); // Skip header row
        $uniqueCsvData = [];

        foreach ($csvDataWithoutHeader as $row) {
            $uniqueCsvData[$row[0]] = $row;  // Use the first column (index 0) as the unique key
        }

            // Reset the keys to re-index the array
            $uniqueCsvData = array_values($uniqueCsvData);
           
        
            // Skip the header row and process the data
            $uniqueCsvData = array_filter($uniqueCsvData, function ($item) {
                return !empty(array_filter($item)); // Remove empty inner arrays
            });
            foreach ($uniqueCsvData as $row) {
                $city = City::where('name', $row[0])->first();
                if($city){
                    // Ensure $row[1] exists and is a string before processing
                    if (!isset($row[1]) || !is_string($row[1])) {
                        // Return an error message if quantity is not numeric
                        return session()->flash('csv_error', 'Skipping row due to missing or invalid data:' . $row[0]);
                    }

                    // Removes spaces around each item
                    $row[1] = array_map('trim', explode(',',$row[1]));
                  
                    // Remove empty values
                    $row[1] = array_values(array_filter($row[1], 'strlen'));

                    if (count($row[1])==0) {
                        // Return an error message if quantity is not numeric
                        return session()->flash('csv_error', 'Please upload atleast one pincode on this city-' . $row[0]);
                    }
                    foreach($row[1] as $key=>$item){
                        $pincode = Pincode::where('pincode', $item)->first();
                        if ($pincode) {
                            // Return an error message if quantity is not numeric
                            return session()->flash('csv_error', 'The pincode ('.$item.') already exists in your system. Please remove it first from city '.$row[0].'.');
                        }
                        $new_pincode = new Pincode();
                        $new_pincode->city_id = $city->id;
                        $new_pincode->pincode = $item;
                        $new_pincode->save();
                    }
                }else{
                    // Return an error message if quantity is not numeric
                    return session()->flash('csv_error', 'The city ('.$row[0].') not exists in your system. Please add it first.');
                }
                
            }
            DB::commit();
            // Flash a success message after processing
            session()->flash('message', 'CSV uploaded and processed successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();
            // Log the exception for debugging
            // \Log::error('Error uploading CSV data: ' . $e->getMessage());
            session()->flash('csv_error', 'Error uploading CSV data: ' . $e->getMessage());
            return;
        }
    
    
        // Remove the file after processing
        // Storage::delete($filePath);
        $filePath = public_path('/storage/csv/pincode/'.$fileName);
        // Check if the file exists and delete it using unlink
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    
        // Reset the file input for future uploads
        $this->reset('csvFile');
        $this->modal_activity_class = 0;
    }
    public function ModalImport($value){
        $this->dispatch('select2_reload');
        $this->modal_activity_class = $value;
    }

    public function deletePinCode($id)
    {
        $this->dispatch('showConfirm', ['itemId' => $id]);
    }

    public function deleteItem($itemId)
    {
        $pincode = Pincode::where('pincode',$itemId)->first();
        if ($pincode) {
            $pincode->delete();
            $this->mount(); // Reset to the first page
            $this->refresh();
            session()->flash('success', 'Pincode deleted successfully!');
        } 
    }

    public function UpdatePinStatus($code){
        $update = Pincode::where('pincode', $code)->first();
        if($update){
            $update->status = $update->status==1?0:1;
            $update->save();
        }else{
            session()->flash('error', 'Sorry! Something went worng with this data '.$code);
        }
    }
    public function render()
    {
        $this->dispatch('select2_reload');
        $this->pincodes = Pincode::query()
        ->when($this->search, function ($query) {
            $query->whereHas('city', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('pincode', 'like', '%' . $this->search . '%'); // Correct placement;
        })
        ->with('city') // Eager load city data
        ->selectRaw('city_id, GROUP_CONCAT(pincode ORDER BY pincode ASC) as pins') // Group pincodes
        ->groupBy('city_id') // Group by city_id
        ->get();
        return view('livewire.admin.pincode-index');
    }
}
