<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Illuminate\Pagination\Paginator;
use App\Exports\SellingQueryExport;
use Maatwebsite\Excel\Facades\Excel;

class SellingQuery extends Component
{
    public $selling_queries = [];
    public $selectedQuery = null;
    public $selectedID = null;

    public function boot(){
      Paginator::useBootstrap();
  }
    public function render()
    {
      $selling_queries = \App\Models\SellingQuery::with([
        'user' => fn($q) => $q->select('id', 'name'),
        'product'  => fn($q) => $q->select('id', 'title'),
    ])->paginate(20);
        return view('livewire.product.selling-query',[
          'data' => $selling_queries,
      ]);
    }
    public function viewQuery($queryId)
    {
        $this->selectedID=$queryId;
        $this->selectedQuery = \App\Models\SellingQuery::with([
            'user:id,name,country_code,mobile,email,address,city,pincode,profile_image',
            'product:id,title',
        ])->find($queryId);
        $this->selectedQuery->phone=$this->selectedQuery->user->country_code.$this->selectedQuery->user->mobile;
        $this->selectedQuery->address=$this->selectedQuery->user->address;
        if(!empty($this->selectedQuery->user->city))
        {
          $this->selectedQuery->address.=$this->selectedQuery->user->city;

        }
        if(!empty($this->selectedQuery->user->pincode))
        {
          $this->selectedQuery->address.=$this->selectedQuery->user->pincode;

        }

    }
    public function exportAll()
    {

        return Excel::download(new SellingQueryExport(), 'selling_queries.xlsx');
    }
}
