<?php

namespace App\Http\Livewire\KnowledgeBase;

use Livewire\Component;

class Edit extends Component
{

    public $question;
    public $answer;
    public $reference;

    public $knowledgeBase;

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

    public function mount($knowledgeBase)
    {
        $this->formValidationStatus = false;


        $this->question = $knowledgeBase->question;
        $this->answer = $knowledgeBase->answer;
        $this->reference = $knowledgeBase->reference;

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
        return view('livewire.knowledge-base.edit');
    }
}
