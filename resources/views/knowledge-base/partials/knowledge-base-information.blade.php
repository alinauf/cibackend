<div x-data="{
            showDeleteModal:false,
                keydown(){
                this.showDeleteModal=false;
            }
    }" class="">
    <div class=" pb-5 flex justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Knowledge Base Information
        </h3>

        <div class="flex">
            <span class="hidden sm:block">
                <a href="{{url("knowledge-base/$knowledgeBase->id/edit")}}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <!-- Heroicon name: solid/pencil -->
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor" aria-hidden="true">
                        <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Edit
                </a>
             </span>
            <span class="hidden sm:block ml-3">
                          <button type="button" @click="showDeleteModal=true"
                                  class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <!-- Heroicon name: solid/link -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500"
                                   viewBox="0 0 20 20"
                                   fill="currentColor">
                                  <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"/>
                              </svg>
                              Delete
                          </button>
                        </span>
        </div>

    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Question
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{$knowledgeBase->question}}
                </dd>
            </div>


            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Answer
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{$knowledgeBase->answer }}
                </dd>
            </div>

            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">
                    Reference
                </dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{$knowledgeBase->reference ?? 'NA'}}
                </dd>
            </div>


        </dl>
    </div>

    <x-confirm-delete-modal post-url="knowledge-base/{{$knowledgeBase->id}}"
                            title="Are you sure"
                            subtitle="Please confirm if you would like to delete the knowledge base"

    />
</div>
