<?php

namespace App\SL;

use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\DB;

class KnowledgeBaseSL extends SL
{
    public function __construct()
    {
        $this->setModel(new KnowledgeBase());
    }

    public function index($search, $paginateCount = 10)
    {
        return KnowledgeBase::where('question', 'like', '%' . $search . '%')
            ->orWhere('answer', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate($paginateCount);
    }


    public function store($data): array
    {
        DB::beginTransaction();
        try {
            $knowledgeBase = KnowledgeBase::firstOrCreate([
                'question' => $data['question'],
                'answer' => $data['answer'],
                'reference' => $data['reference'],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        if ($knowledgeBase) {
            return [
                'status' => true,
                'payload' => 'The question and answer has been successfully created',
            ];
        } else {
            return [
                'status' => false,
                'payload' => 'There was an issue with saving the question and answer',
            ];
        }
    }

    /**
     * @throws \Exception
     */
    public function update($knowledgeBaseId, $data): bool|array
    {
        DB::beginTransaction();

        $knowledgeBase = KnowledgeBase::where('id', $knowledgeBaseId)->first();

        try {
            $knowledgeBase->question = $data['question'] ?? $knowledgeBase->question;
            $knowledgeBase->answer = $data['answer'] ?? $knowledgeBase->answer;
            $knowledgeBase->reference = $data['reference'] ?? $knowledgeBase->reference;
            $knowledgeBaseSave = $knowledgeBase->save();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        if ($knowledgeBaseSave) {
            return [
                'status' => true,
                'payload' => 'The question and answer has been successfully updated',
            ];
        } else {
            return [
                'status' => false,
                'payload' => 'There was an issue with updating the knowledge base',
            ];
        }
    }


}
