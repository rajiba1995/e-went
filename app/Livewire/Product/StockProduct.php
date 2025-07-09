<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination; // Import WithPagination trait
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\StockLedger;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockProduct extends Component
{
    use WithFileUploads, WithPagination; // Include WithPagination trait

    public $search = '';
    public $csvFile; 
    public $products;
    public $modal_activity_class = 0;

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
        $filePath = $file->storeAs('public/csv', $fileName);

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
                
                $product = Product::where('product_sku', $row[0])->first();
                if($product){
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
                        return session()->flash('csv_error', 'Please upload atleast one vehicle' . $row[0]);
                    }

                    // Fetch the product by product_sku (index 0)
                    $product->stock_qty = $product->stock_qty + (int)count($row[1]); // Convert quantity to integer
                    $product->stock = 1;
                    $product->save();
                    
                    $stock = new StockLedger();
                    $stock->product_id = $product->id;
                    $stock->quantity = (int)count($row[1]);
                    $stock->purpose = 'New';
                    $stock->type = 'Credit';
                    $stock->save();

                    foreach($row[1] as $key=>$item){
                        $stock = Stock::where('vehicle_number', strtoupper($item))->first();
                        if ($stock) {
                            // Return an error message if quantity is not numeric
                            return session()->flash('csv_error', 'The vehicle number ('.$item.') already exists in your system. Please remove it first from SKU '.$row[0].'.');
                        }
                        $new_stock = new Stock();
                        $new_stock->product_id = $product->id;
                        $new_stock->vehicle_number = strtoupper($item);
                        $new_stock->save();
                    }
                }else{
                    // Return an error message if quantity is not numeric
                    return session()->flash('csv_error', 'The Product SKU ('.$row[0].') not exists in your system. Please remove it first.');
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
        $filePath = public_path('/storage/csv/'.$fileName);
        // Check if the file exists and delete it using unlink
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    
        // Reset the file input for future uploads
        $this->reset('csvFile');
        $this->modal_activity_class = 0;
    }
    public function ModalActivity($value){
        $this->modal_activity_class = $value;
        $this->resetSearch();
    }

    public function searchButtonClicked(){
        $this->resetPage(); // Reset to the first page
    }
    public function resetSearch()
    {
        $this->reset(['csvFile', 'search']);
        $this->resetPage();     // Reset pagination
    }
    public function render()
    {

        $stock = Stock::query()
            ->when($this->search, function ($query) {
                $query->orWhereHas('product', function ($query) {
                    $query->whereNull('deleted_at') // Ensure product is not soft-deleted
                        ->where(function ($subQuery) { // Group conditions properly
                            $subQuery->where('title', 'like', '%' . $this->search . '%')
                                ->orWhere('product_sku', 'like', '%' . $this->search . '%')
                                ->orWhere('types', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->with(['product'])
            ->selectRaw('
                product_id, 
                COUNT(*) as stock_count, 
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as available_quantity
            ')
            ->groupBy('product_id') // Group by product_id
            ->paginate(10);


        return view('livewire.product.stock-product', [
            'stocks' => $stock
        ]);
    }
}
