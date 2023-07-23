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
                'reference' => $data['reference'] ?? null,
            ]);

            $dialogflowSL = new DialogFlowSL();
            $intentId = $dialogflowSL->intent_create('chatislam', 'knowledge-' . $knowledgeBase->id, [$data['question']], [$data['answer']]);
            $knowledgeBase->intent_id = $intentId;
            $result = $knowledgeBase->save();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        if ($knowledgeBase && $result) {
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

            $question = $data['question'] ?? $knowledgeBase->question;
            $answer = $data['answer'] ?? $knowledgeBase->answer;


            $knowledgeBase->question = $question;
            $knowledgeBase->answer = $answer;
            $knowledgeBase->reference = $data['reference'] ?? $knowledgeBase->reference;
            $knowledgeBaseSave = $knowledgeBase->save();


            $dialogFlowSL = new DialogFlowSL();

            if ($knowledgeBase->intent_id) {
                $dialogFlowSL->intent_delete('chatislam',  $knowledgeBase->intent_id);
            }

            $dialogFlowSL->intent_create('chatislam', 'knowledge-' . $knowledgeBase->id, [$question], [$answer]);


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
