<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\WhyEwent;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class WhyEwentIndex extends Component
{

    use WithFileUploads;
    public $why_ewents;
    public $why_ewent_id,$title, $image, $status, $search;

    protected $rules=[
        'title'=>'required|string|max:255',
        'image'=>'nullable|mimes:jpg,jpeg,png,gif',
    ];

    public function mount(){
        $this->refresh();
    }

    public function refresh(){
        $this->resetForm();
        $this->search = '';
        $this->why_ewents = WhyEwent::where('title', 'like', '%'.$this->search.'%')->get();
    }
    public function resetForm(){
        $this->reset(['why_ewent_id', 'title', 'image', 'status']);
    }

    public function save(){
        $rules =$this->rules;
        if($this->why_ewent_id){
            $rules['title'].='|unique:why_ewent,title,'.$this->why_ewent_id;
        }else{
            $rules['title'].='|unique:why_ewent,title';
            $rules['image'] = 'required|mimes:jpg,jpeg,png,gif';
        }

        $this->validate($rules);

        if($this->why_ewent_id){
            $update = WhyEwent::findOrFail($this->why_ewent_id);
            $update->title = ucwords($this->title);
            if($this->image){
                $update->image = storeFileWithCustomName($this->image, 'upload/banner');
            }
            $update->save();
            session()->flash('message', 'Data updated successfully');
        }else{
            $store = new WhyEwent;
            $store->title = ucwords($this->title);
            if($this->image){
                $store->image = storeFileWithCustomName($this->image, 'upload/banner');
            }
            $store->save();
            session()->flash('message', 'Data inserted successfully');
        }
        $this->resetForm();
        $this->refresh();
    }

    public function edit($id){
        $data = WhyEwent::findOrFail($id);
        $this->why_ewent_id = $data->id;
        $this->title = $data->title;
        $this->status = $data->status;
    }

    public function destroy($id){
        $delete = WhyEwent::findOrFail($id)->delete();
        session()->flash('message', 'Data deleted successfully');
        $this->refresh();
    }

    public function toggleStatus($id){
        $data = WhyEwent::findOrFail($id);
        $data->status = !$data->status;
        $data->save();
        session()->flash('message', 'Status updated successfully');
        $this->refresh();
    }
    public function render()
    {
        return view('livewire.master.why-ewent-index');
    }

    
}
