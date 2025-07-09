<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Stock;
use App\Models\Product;

class VehicleList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $model;
    public $search = '';
    public $active_tab = 1;
    public $models = [];
    public $isModalOpen = false; // Track modal visibility

    /**
     * Search button click handler to reset pagination.
     */
    public function mount(){
        $this->models = Product::where('status', 1)->orderBy('title', 'ASC')->get();
    }
    public function btn_search()
    {
      
    }


    public function closeModal()
    {
        $this->isModalOpen = false;
    }
    public function FilterModel($value){
        $this->model =$value;
    }

    /**
     * Refresh button click handler to reset the search input and reload data.
     */
    public function reset_search(){
        $this->reset(['search','model']); // Reset the search term
    }

    public function tab_change($value){
        $this->active_tab = $value;
        $this->reset_search();
    }
    public function render()
    {
        // Fetch all vehicles (with or without assigned vehicles)
        $all_vehicles = Stock::with('product','assignedVehicle')
        ->when($this->model, function ($query) {
            $query->where('product_id', $this->model); // Assuming `model_id` is the column for filtering
        })
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';
            $query->where('vehicle_number', 'like', $searchTerm)
                ->orWhere('imei_number', 'like', $searchTerm)
                ->orWhere('chassis_number', 'like', $searchTerm)
                ->orWhere('friendly_name', 'like', $searchTerm);
        })
        ->orderBy('id', 'DESC')
        ->orderBy('product_id', 'DESC')
        ->paginate(20);

        // Fetch only assigned vehicles (having an entry in the assigned_vehicles table)
        $assigned_vehicles = Stock::with('assignedVehicle')
        ->whereHas('assignedVehicle') // Ensures only assigned vehicles are fetched
        ->when($this->model, function ($query) {
            $query->where('product_id', $this->model); // Assuming `model_id` is the column for filtering
        })
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';
            $query->where('vehicle_number', 'like', $searchTerm)
                ->orWhere('imei_number', 'like', $searchTerm)
                ->orWhere('chassis_number', 'like', $searchTerm)
                ->orWhere('friendly_name', 'like', $searchTerm);
        })
        ->orderBy('id', 'DESC')
        ->orderBy('product_id', 'DESC')
        ->paginate(20);
        
        
        $unassigned_vehicles = Stock::whereDoesntHave('assignedVehicle', function ($query) {
            $query->whereIn('status', ['assigned','sold']); // Ensure it's truly unassigned
        })->whereDoesntHave('overdueVehicle', function ($query) {
            $query->whereIn('status', ['overdue']); // Ensure it's truly unassigned
        })
        ->when($this->model, function ($query) {
            $query->where('product_id', $this->model); // Assuming `model_id` is the column for filtering
        })
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vehicle_number', 'like', $searchTerm)
                    ->orWhere('imei_number', 'like', $searchTerm)
                    ->orWhere('chassis_number', 'like', $searchTerm)
                    ->orWhere('friendly_name', 'like', $searchTerm);
            });
        })
        ->orderBy('id', 'DESC')
        ->paginate(20);

        
        $overdue_vehicles = Stock::with('overdueVehicle')
        ->whereHas('overdueVehicle') // Ensures only assigned vehicles are fetched
        ->when($this->model, function ($query) {
            $query->where('product_id', $this->model); // Assuming `model_id` is the column for filtering
        })
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';
            $query->where('vehicle_number', 'like', $searchTerm)
                ->orWhere('imei_number', 'like', $searchTerm)
                ->orWhere('chassis_number', 'like', $searchTerm)
                ->orWhere('friendly_name', 'like', $searchTerm);
        })
        ->orderBy('id', 'DESC')
        ->orderBy('product_id', 'DESC')
        ->paginate(20);

        return view('livewire.product.vehicle-list', [
            'all_vehicles' => $all_vehicles,
            'unassigned_vehicles' => $unassigned_vehicles,
            'assigned_vehicles' => $assigned_vehicles,
            'overdue_vehicles' => $overdue_vehicles,
        ]);
    }
}
