<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\SL\DialogFlowSL;
use App\SL\KnowledgeBaseSL;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    private KnowledgeBaseSL $knowledgeBaseService;

    public function __construct(KnowledgeBaseSL $knowledgeBaseService)
    {
        $this->knowledgeBaseService = $knowledgeBaseService;
    }

    public function dashboard()
    {
        $knowledgeCount = KnowledgeBase::count();
        return view('dashboard', ['knowledgeCount' => $knowledgeCount]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('knowledge-base.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('knowledge-base.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->knowledgeBaseService->store($request->all());

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($result['status']) {
            return redirect('knowledge-base')->with('success', $result['payload']);
        } else {
            return redirect()->back()->with('errors', $result['payload']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeBase $knowledgeBase)
    {
        return view('knowledge-base.show', ['knowledgeBase' => $knowledgeBase]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeBase $knowledgeBase)
    {
        return view('knowledge-base.edit', ['knowledgeBase' => $knowledgeBase]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KnowledgeBase $knowledgeBase)
    {
        $result = $this->knowledgeBaseService->update($knowledgeBase->id, $request->all());

        if ($result['status']) {
            return redirect('knowledge-base')->with('success', $result['payload']);
        } else {
            return redirect()->back()->with('errors', $result['payload']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeBase $knowledgeBase)
    {
        $intentId = $knowledgeBase->intent_id ?? null;
        $result = $this->knowledgeBaseService->destroy($knowledgeBase->id);

        if ($result['status']) {
            if ($intentId) {
                $dialogFlowSL = new DialogFlowSL();
                $dialogFlowSL->intent_delete('chatislam', $intentId);
            }

            return redirect('knowledge-base')->with('success', $result['payload']);
        } else {
            return redirect()->back()->with('errors', 'Something went wrong.');
        }
    }
}
