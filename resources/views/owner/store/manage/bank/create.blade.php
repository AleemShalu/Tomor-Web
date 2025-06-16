<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <div class="w-full lg:w-2/3 mx-auto pb-4">
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

        <div class="w-full lg:w-2/3 mx-auto bg-white rounded p-6 shadow-md">
            <h2 class="text-base font-semibold leading-7 text-gray-900">
                <i class="fas fa-money-check-alt mr-2 mx-2"></i>
                {{__('locale.bank_account.add_bank_account')}}
            </h2>

            @if ($errors->any())
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('bank.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="store_id" name="store_id" value="{{$store->id}}">

                <div class="mt-6">
                    <label for="iban_number" class="block text-sm font-medium text-gray-700">
                        {{__('locale.bank_account.iban_number')}}
                    </label>
                    <input type="text" name="iban_number" id="iban_number" value="{{ old('iban_number') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('iban_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="account_holder_name" class="block text-sm font-medium text-gray-700">
                        {{__('locale.bank_account.account_holder_name')}}
                    </label>
                    <input type="text" name="account_holder_name" id="account_holder_name"
                           value="{{ old('account_holder_name') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('account_holder_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">
                        {{__('locale.bank_account.name_bank')}}
                    </label>
                    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('bank_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="iban_attachment" class="block text-sm font-medium text-gray-700">
                        {{__('locale.bank_account.iban_attachment')}}
                    </label>
                    <input type="file" name="iban_attachment" id="iban_attachment" accept=".pdf"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('iban_attachment')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <div class="mt-6">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-plus-circle mr-2"></i>
                        {{__('locale.bank_account.add_bank_account')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    function goBack() {
        window.history.back();
    }
</script>
