<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <div class="w-full lg:w-2/3 mx-auto">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{route('dashboard')}}"
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{route('store')}}"
                               class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Store</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{route('settings.manage',['id'=>$store->id])}}"
                               class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{$store->commercial_name_en}}</a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">


        @foreach ($bankAccountBranches as $account)
            <div class="w-full lg:w-2/3 mx-auto bg-white rounded p-6 shadow-md mb-8"
                 id="print-section-info-{{ $account->id }}">
                <h2 class="text-2xl font-semibold mb-4">
                    {{__('locale.bank_account.bank_information')}}
                </h2>
                <div class="border-b border-gray-300 pb-6 mb-6">
                    <div class="flex flex-wrap mb-4">
                        <div class="w-full sm:w-1/2">
                            <p class="mb-2"><strong><i class="fas fa-users mr-2"></i>
                                    {{__('locale.bank_account.account_holder_name')}} :
                                </strong> {{ $account->account_holder_name }}</p>
                            <p class="mb-2"><strong><i class="fas fa-credit-card mr-2"></i>
                                    {{__('locale.bank_account.iban_number')}} :</strong> {{ $account->iban_number }}
                            </p>
                            <p class="mb-2"><strong><i class="fas fa-university mr-2"></i>
                                    {{__('locale.bank_account.name_bank')}} :
                                </strong> {{ $account->bank_name ?: 'N/A' }}</p>
                        </div>

                    </div>
                    <div class="flex flex-wrap mb-4 hidden">
                        <div class="w-full sm:w-1/2">
                            <p class="mb-2"><strong><i class="fas fa-calendar-alt mr-2"></i>Created
                                    At:</strong> {{ $account->created_at }}</p>
                        </div>
                        <div class="w-full sm:w-1/2">
                            <p class="mb-2"><strong><i class="fas fa-calendar-alt mr-2"></i>Updated
                                    At:</strong> {{ $account->updated_at }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                            onclick="printInfo('print-section-info-{{ $account->id }}')">
                        {{__('locale.bank_account.print')}}
                    </button>
                </div>
            </div>
        @endforeach
        {{--            @foreach ($bankAccountBranches as $bankAccountBranch)--}}
        {{--                <div class="w-full lg:w-2/3 mx-auto bg-white rounded p-6 shadow-md mb-8" id="print-section-{{ $bankAccountBranch->id }}">--}}
        {{--                    <h2 class="text-3xl font-semibold mb-6">Bank Account Details - Branch ID: {{ $bankAccountBranch->id }}</h2>--}}

        {{--                    <!-- Store Information -->--}}
        {{--                    <div class="border-b border-gray-300 pb-6 mb-6">--}}
        {{--                        <h3 class="text-xl font-semibold mb-4">Store Information</h3>--}}
        {{--                        @if (!empty($bankAccountBranch->store))--}}
        {{--                            <div class="flex flex-wrap mb-4">--}}
        {{--                                <div class="w-full sm:w-1/2">--}}
        {{--                                    <p class="mb-2"><strong><i class="fas fa-store mr-2"></i>Store Name:</strong>--}}
        {{--                                        {{ $bankAccountBranch->store->commercial_name_en }}--}}
        {{--                                    </p>--}}
        {{--                                    <p class="mb-2"><strong><i class="fas fa-envelope mr-2"></i>Email:</strong>--}}
        {{--                                        {{ $bankAccountBranch->store->email }}--}}
        {{--                                    </p>--}}
        {{--                                    <p class="mb-2"><strong><i class="fas fa-phone mr-2"></i>Contact Number:</strong>--}}
        {{--                                        {{ $bankAccountBranch->store->dial_code }}{{ $bankAccountBranch->store->contact_no }}--}}
        {{--                                    </p>--}}
        {{--                                    <p class="mb-2"><strong><i class="fas fa-globe mr-2"></i>Website:</strong>--}}
        {{--                                        <a href="{{ $bankAccountBranch->store->website }}" class="text-blue-500 hover:underline">--}}
        {{--                                            {{ $bankAccountBranch->store->website }}--}}
        {{--                                        </a>--}}
        {{--                                    </p>--}}
        {{--                                </div>--}}
        {{--                                <div class="w-full sm:w-1/2 flex justify-center">--}}
        {{--                                    <img src="{{ url('storage/'.$bankAccountBranch->store->logo) }}" alt="Store Logo"--}}
        {{--                                         class="w-48 h-48 object-contain">--}}
        {{--                                </div>--}}
        {{--                            </div>--}}
        {{--                            <p class="mb-2"><strong><i class="fas fa-info-circle mr-2"></i>Description:</strong>--}}
        {{--                                {{ $bankAccountBranch->store->description }}--}}
        {{--                            </p>--}}
        {{--                        @else--}}
        {{--                            <p>No store information available</p>--}}
        {{--                        @endif--}}
        {{--                    </div>--}}

        {{--                    <!-- Branch Information -->--}}
        {{--                    <div class="border-b border-gray-300 pb-6 mb-6">--}}
        {{--                        <h3 class="text-xl font-semibold mb-4">Branch Information</h3>--}}
        {{--                        @if (!empty($bankAccountBranch->branches))--}}
        {{--                            @foreach ($bankAccountBranch->branches as $branch)--}}
        {{--                                <div class="flex flex-wrap mb-4">--}}
        {{--                                    <div class="w-full sm:w-1/2">--}}
        {{--                                        <p class="mb-2"><strong><i class="fas fa-user mr-2"></i>Branch Name:</strong>--}}
        {{--                                            {{ $branch->name }}--}}
        {{--                                        </p>--}}
        {{--                                        <p class="mb-2"><strong><i class="fas fa-id-badge mr-2"></i>Commercial Registration No:</strong>--}}
        {{--                                            {{ $branch->commercial_registration_no }}--}}
        {{--                                        </p>--}}
        {{--                                        <p class="mb-2"><strong><i class="fas fa-envelope mr-2"></i>Email:</strong>--}}
        {{--                                            {{ $branch->email }}--}}
        {{--                                        </p>--}}
        {{--                                        <p class="mb-2"><strong><i class="fas fa-phone mr-2"></i>Contact Number:</strong>--}}
        {{--                                            {{ $branch->dial_code }}{{ $branch->contact_no }}</p>--}}
        {{--                                    </div>--}}
        {{--                                    <div class="w-full sm:w-1/2 flex justify-center">--}}
        {{--                                        <img src="{{ url('storage/'.$branch->qr_code) }}" alt="QR Code"--}}
        {{--                                             class="w-48 h-48 object-contain">--}}
        {{--                                    </div>--}}
        {{--                                </div>--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-map-marker-alt mr-2"></i>City:</strong>--}}
        {{--                                    {{ $branch->city->en_name }}--}}
        {{--                                </p>--}}
        {{--                            @endforeach--}}
        {{--                        @else--}}
        {{--                            <p>No branch information available</p>--}}
        {{--                        @endif--}}
        {{--                    </div>--}}

        {{--                    <!-- Bank Account Information -->--}}
        {{--                    <div class="border-b border-gray-300 pb-6 mb-6">--}}
        {{--                        <h3 class="text-xl font-semibold mb-4">Bank Account Information</h3>--}}
        {{--                        <div class="flex flex-wrap mb-4">--}}
        {{--                            <div class="w-full sm:w-1/2">--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-users mr-2"></i>Account Holder Name:</strong> {{ $bankAccountBranch->account_holder_name }}</p>--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-credit-card mr-2"></i>IBAN Number:</strong> {{ $bankAccountBranch->iban_number }}</p>--}}
        {{--                            </div>--}}
        {{--                            <div class="w-full sm:w-1/2">--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-university mr-2"></i>Bank Name:</strong> {{ $bankAccountBranch->bank_name ?: 'N/A' }}</p>--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-dollar-sign mr-2"></i>Swift Code:</strong> {{ $bankAccountBranch->swift_code ?: 'N/A' }}</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}

        {{--                    <!-- Additional Information -->--}}
        {{--                    <div>--}}
        {{--                        <h3 class="text-xl font-semibold mb-4">Additional Information</h3>--}}
        {{--                        <div class="flex flex-wrap mb-4">--}}
        {{--                            <div class="w-full sm:w-1/2">--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-calendar-alt mr-2"></i>Created At:</strong> {{ $bankAccountBranch->created_at }}</p>--}}
        {{--                            </div>--}}
        {{--                            <div class="w-full sm:w-1/2">--}}
        {{--                                <p class="mb-2"><strong><i class="fas fa-calendar-alt mr-2"></i>Updated At:</strong> {{ $bankAccountBranch->updated_at }}</p>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="flex justify-end mt-8">--}}
        {{--                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded" onclick="printSection('print-section-{{ $bankAccountBranch->id }}')">--}}
        {{--                            Print--}}
        {{--                        </button>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            @endforeach--}}
    </div>
</x-app-layout>
<script>
    function printSection(sectionId) {
        var printContents = document.getElementById(sectionId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function printInfo(sectionId) {
        var printContents = document.getElementById(sectionId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
