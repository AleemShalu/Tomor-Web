<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Owner') }}
        </h2>
    </x-slot>


    <div class="py-12" style="padding-left: 10%; padding-right: 10%">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="font-semibold text-xl text-gray-800 leading-tight pl-3 pt-6">Stores</div>
            <x-store-index></x-store-index>
            </div>
    </div>

    <div class="py-12" style="padding-left: 10%; padding-right: 10%">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="font-semibold text-xl text-gray-800 leading-tight pl-3 pt-6">Branches</div>
            <x-store-branch></x-store-branch>
        </div>
    </div>
</x-app-layout>
