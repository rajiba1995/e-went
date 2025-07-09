<?php

namespace App\Livewire\Master;

use Livewire\Component;
use App\Models\Faq;

class FaqIndex extends Component
{
    public $faqId, $question, $answer;
    public $search = '';
    public $faqs = [];

    protected $rules = [
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
    ];

    public function mount()
    {
        $this->faqs = FAQ::all();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetForm()
    {
        $this->faqId = null;
        $this->question = '';
        $this->answer = '';
    }

    public function refresh(){
        $this->resetForm();
        $this->search = '';
        $this->faqs = FAQ::all();
    }
    public function store()
    {
        $this->validate();

        FAQ::updateOrCreate(['id' => $this->faqId], [
            'question' => $this->question,
            'answer' => $this->answer,
        ]);

        session()->flash('message', $this->faqId ? 'FAQ updated successfully.' : 'FAQ created successfully.');

        $this->resetForm();
        $this->faqs = FAQ::all();
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);

        $this->faqId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
    }

    public function delete($id)
    {
        FAQ::findOrFail($id)->delete();

        session()->flash('message', 'FAQ deleted successfully.');

        $this->faqs = FAQ::all();
    }
    public function searchFaq()
    {
        $this->faqs = FAQ::where('question', 'like', '%' . $this->search . '%')->orWhere('answer', 'like', '%' . $this->search . '%')->get();
    }

    public function render()
    {
        // $filteredFaqs = FAQ::where('question', 'like', '%' . $this->search . '%')->get();
        $this->dispatch('ck_editor_load');
        return view('livewire.master.faq-index', ['faqs' =>  $this->faqs]);
    }
}




