<?php

namespace App\Http\Livewire\KnowledgeBase;

use Livewire\Component;

class Create extends Component
{

    public $question;
    public $answer;
    public $reference;

    public $formValidationStatus;

    protected $rules = [
        'question' => 'required|unique:knowledge_bases,question',
        'answer' => 'required|required',
        'reference' => 'nullable',
    ];

    protected $messages =
        [
            'question.required' => 'Enter the question',
            'answer.required' => 'Enter the answer',
        ];

    public function mount()
    {
        $this->formValidationStatus = false;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function validateForm()
    {
        if ($this->validate()) {
            $this->formValidationStatus = true;
        } else {
            $this->formValidationStatus = false;
        }
    }


    public function render()
    {
        return view('livewire.knowledge-base.create');
    }
}
