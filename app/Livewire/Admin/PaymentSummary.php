<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Stock;
use App\Models\Product;
use App\Models\PaymentItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentSummaryExport;

class PaymentSummary extends Component
{
    public $vehicle,$deposit_amount,$rental_amount;
    public $models = [];
    public $data = [];
    public $expandedRows = [];
    public $model,$model_id,$vehicle_id,$start_date,$end_date;
    public function mount($model_id = null,$vehicle_id = null){
        
        if($model_id){
            $this->model =Product::find($model_id);
            if(!$this->model){
                $this->model_id = $model_id;
                abort(404);
            } 
        }
        if($vehicle_id){
            $this->vehicle =Stock::find($vehicle_id);
            $this->expandedRows[] = 0;
            if(!$this->vehicle){
                $this->vehicle_id = $vehicle_id;
                abort(404);
            } 
        }
        
        $this->models = Product::where('status', 1)->orderBy('title', 'ASC')->get();
    }

    public function FilterModel($value){
        $this->resetPageField();
        $this->model =Product::find($value);
        $this->model_id =$value;
    }
    public function resetPageField(){
        $this->reset(['vehicle_id','model_id','data','model','vehicle', 'start_date', 'end_date']);
    }
    public function toggleRow($key)
    {
        if (in_array($key, $this->expandedRows)) {
            $this->expandedRows = array_diff($this->expandedRows, [$key]);
        } else {
            $this->expandedRows[] = $key;
        }
    }

    public function updateDate($field, $value){
        $this->$field = $value;
    }
    public function GetDepositAmount(){
      
        $this->deposit_amount = PaymentItem::when($this->start_date && $this->end_date, function ($query) {
           return $query->whereBetween('created_at', [
                $this->start_date . ' 00:00:00', 
                $this->end_date . ' 23:59:59'
            ]);
        })
        ->when($this->vehicle_id, function ($query) {
            return $query->where('vehicle_id', $this->vehicle_id);
        })
        ->when($this->model_id, function ($query) {
            return $query->where('product_id', $this->model_id);
        })
        ->where('type', 'deposit')
        ->sum('amount');
    }
    public function GetRentalAmount(){
        $this->rental_amount = PaymentItem::when($this->start_date && $this->end_date, function ($query) {
           return $query->whereBetween('created_at', [
                $this->start_date . ' 00:00:00', 
                $this->end_date . ' 23:59:59'
            ]);
        })
        ->when($this->vehicle_id, function ($query) {
            return $query->where('vehicle_id', $this->vehicle_id);
        })
        ->when($this->model_id, function ($query) {
            return $query->where('product_id', $this->model_id);
        })
        ->where('type', 'rental')
        ->sum('amount');
    }
    public function GetPaymentLogForModels(){

        $results = Product::whereHas('payment_item', function ($query) {
            if ($this->start_date && $this->end_date) {
                $query->whereBetween('created_at', [
                    $this->start_date . ' 00:00:00', 
                    $this->end_date . ' 23:59:59'
                ]);
            }
        })->when($this->model_id, function ($query) {
            return $query->where('id', $this->model_id);
        })->get();

      $this->reset(['data']);
        foreach($results as $key=>$item){
       
           $vehicles = $item->stock_item
            ->when($this->vehicle_id, function ($query) {
                return $query->where('id', $this->vehicle_id);
            })->pluck('id')->toArray();
            
            $this->data[$key] =[
                'model_id'=>$item->id,
                'title'=>$item->title,
                'types'=>$item->types,
                'image'=>$item->image,
                'deposit_amount'=>$item->payment_item->where('type', 'deposit')
                ->when($this->start_date && $this->end_date, function ($query) {
                    return $query->whereBetween('created_at', [
                        $this->start_date . ' 00:00:00', 
                        $this->end_date . ' 23:59:59'
                    ]);
                })
                ->sum('amount'),
                'rental_amount'=>$item->payment_item->where('type', 'rental')->when($this->start_date && $this->end_date, function ($query) {
                    return $query->whereBetween('created_at', [
                        $this->start_date . ' 00:00:00', 
                        $this->end_date . ' 23:59:59'
                    ]);
                })->sum('amount'),
                'total_amount'=>$item->payment_item->when($this->start_date && $this->end_date, function ($query) {
                    return $query->whereBetween('created_at', [
                        $this->start_date . ' 00:00:00', 
                        $this->end_date . ' 23:59:59'
                    ]);
                })->sum('amount'),
                'vehicles'=>[]
            ];

            foreach($vehicles as $k=>$vehicle){
                $PaymentItem = PaymentItem::with('stock')->when($this->start_date && $this->end_date, function ($query) {
                    return $query->whereBetween('created_at', [
                        $this->start_date . ' 00:00:00', 
                        $this->end_date . ' 23:59:59'
                    ]);
                })
                ->when($vehicle, function ($query) use ($vehicle) {
                    return $query->where('vehicle_id', $vehicle);
                })->whereNotNull('vehicle_id')
                ->get();

                $stock = Stock::find($vehicle);
                $this->data[$key]['vehicles'][$k] =[
                    'vehicle_number' =>$stock->vehicle_number,
                    'deposit_amount' => $PaymentItem->where('type', 'deposit')->sum('amount'),
                    'rental_amount' =>$PaymentItem->where('type', 'rental')->sum('amount'),
                    'total_amount' =>$PaymentItem->sum('amount'),
                ];
            }
           
        }

    }

    public function exportAll()
    {
        return Excel::download(new PaymentSummaryExport($this->data, $this->start_date, $this->end_date), 'payment_summary.xlsx');
    }
    public function render()
    {
       $this->GetDepositAmount();
       $this->GetRentalAmount();
       $this->GetPaymentLogForModels();

        return view('livewire.admin.payment-summary');
    }
}
