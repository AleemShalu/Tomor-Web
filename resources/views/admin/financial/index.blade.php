<x-app-admin-layout>
    <div class="w-1/2 lg:w-8/12 px-7 mx-auto mt-6">
        <div class="bg-white py-6 px-4 font-bold text-xl rounded">
            <div class="flex">
                <i class="mx-3 mt-1" data-lucide="badge-dollar-sign"></i>
                {{__('admin.financial_management.financial_management')}}
            </div>
        </div>
        <div class="bg-white mt-4 py-6 px-6 w-full ">
            <div class="w-full ">
                <div class="font-inter text-2xl">
                    {{__('admin.financial_management.financial_invoices')}}
                </div>
                <div class="mt-2 font-light text-gray-600">
                    {{__('admin.financial_management.financial_invoices_dec')}}
                </div>
                <div class="mt-4">
                    <a href="{{route('admin.financial.invoices.index')}}"
                       class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded mt-4">
                        {{__('admin.financial_management.show_all_invoices')}}
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white mt-4 py-6 px-6 w-full ">
            <div class="w-full ">
                <div class="font-inter text-2xl">
                    {{__('admin.financial_management.financial_stores_statistics')}}
                </div>
                <div class="mt-2 font-light text-gray-600">
                    {{__('admin.financial_management.financial_stores_statistics_dec')}}
                </div>

                <div class="mt-4">
                    <a href="{{route('admin.financial.store-analysis.index')}}"
                       class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded mt-4">
                        {{__('admin.financial_management.show')}}
                    </a>
                </div>
            </div>
        </div>


    </div>
</x-app-admin-layout>
