<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div>
                <h3 class="text-base font-semibold leading-6 text-gray-900">Stats</h3>
                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                        <dt class="truncate text-sm font-medium text-gray-500">Total Questions learnt</dt>
                        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{$knowledgeCount}}</dd>
                    </div>
                </dl>
            </div>


        </div>
    </div>
</x-app-layout>
