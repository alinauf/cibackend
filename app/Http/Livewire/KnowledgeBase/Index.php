<?php

namespace App\Http\Livewire\KnowledgeBase;

use App\SL\KnowledgeBaseSL;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;

    public function render()
    {
        $service = new KnowledgeBaseSL();
        $data = $service->indexSearch('question', $this->search);

        return view('livewire.knowledge-base.index',['knowledgeBases' => $data]);
    }
}
