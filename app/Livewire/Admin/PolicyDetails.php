<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Policy;

class PolicyDetails extends Component
{
    public $active_tab = 1;
    public $policies = [];
    public $policyId;
    public $title;
    public $content;

    protected $rules = [
        'content' => 'required|string',
    ];

    public function mount(){
        $this->resetErrorBag();
        $this->policies = Policy::orderBy('title', 'ASC')->get();
    }
    public function ActiveCreateTab($value){
        $this->resetForm();
        $this->active_tab = $value;
    }

    public function store(){
        $this->rules['title'] = 'required|string|max:255|unique:policies,title';
        $this->validate();
        Policy::create([
            'title'=>$this->title,
            'content'=>$this->content,
        ]);
        session()->flash('success', 'Data created successfully!');
        $this->resetForm();
    }

    public function EditItem($id){
        $policy = Policy::findOrFail($id);
        $this->policyId = $policy->id;
        $this->title = $policy->title;
        $this->content = $policy->content;
        $this->active_tab = 3;
    }

    public function updateItem(){
        $this->rules['title'] = 'required|string|max:255|unique:policies,title, ' . $this->policyId;
        $this->validate();
        $update = Policy::findOrFail($this->policyId);
        $update->update([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        session()->flash('success', 'Data updated successfully!');
        $this->resetForm();
    }
    public function resetForm(){
        $this->policyId = null;
        $this->title = '';
        $this->content = '';
        $this->reset();
        $this->mount();
    }

    public function render()
    {
        $this->dispatch('ck_editor_load');
        return view('livewire.admin.policy-details', [
            'policies'=>$this->policies,
        ]);
    }
}
