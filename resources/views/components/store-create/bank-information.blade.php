<div id="step3" class="border-gray-900/10 pb-12" style="display: none;">
    <div class="border-b border-gray-900/10 pb-5">
        <h2 class="text-base font-semibold leading-7 text-gray-900">
            <i class="fas fa-info-circle mr-2"></i>{{ __('locale.bank.bank_information') }}
        </h2>
        <div
                class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 bg-gray-100 p-4 rounded-l-md">
            <div class="sm:col-span-3">
                <label for="account_holder_name" class="block text-sm font-medium text-gray-700">
                    <span style="color: red">*</span>
                    {{ __('locale.bank.account_holder_name') }}
                </label>
                <input type="text" name="account_holder_name" id="account_holder_name"
                       autocomplete="off" placeholder="AHMED WALLED"
                       value="{{ old('account_holder_name') }}"
                       class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <!-- Error message element for account_holder_name -->
                <p id="account_holder_name_error" class="text-sm text-red-500"></p>
            </div>
            <div class="sm:col-span-3">
                <label for="iban_number" class="block text-sm font-medium text-gray-700">
                    <span style="color: red">*</span>
                    {{ __('locale.bank.iban_number') }}
                </label>
                <input type="text" name="iban_number" id="iban_number" autocomplete="off"
                       value="{{ old('iban_number') }}"
                       maxlength="24" placeholder="SA0380000000608010167519"
                       class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full
                                    shadow-sm sm:text-sm border-gray-300 rounded-md">
                <!-- Error message element for iban_number -->
                <p id="iban_number_error" class="text-sm text-red-500"></p>
            </div>
            <div class="sm:col-span-3">
                <label for="iban_attachment" class="block text-sm font-medium text-gray-700">
                    <span style="color: red">*</span>
                    {{ __('locale.bank.iban_attachment') }}
                </label>
                <input type="file" name="iban_attachment" id="iban_attachment"
                       oninput="validateFileTypePdf(this)"
                       accept=".pdf"
                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-white border border-gray-200 mt-1">
                <!-- Error message element for iban_attachment -->
                <p id="iban_attachment_error" class="text-sm text-red-500"></p>
            </div>
            <div class="sm:col-span-3">
                <label for="bank_name" class="block text-sm font-medium text-gray-700">
                    <span style="color: red">*</span>
                    {{ __('locale.bank.name_bank') }}
                </label>
                <input type="text" name="bank_name" id="bank_name" autocomplete="off"
                       placeholder="AlRajh"
                       value="{{ old('bank_name') }}"
                       class="move-on-enter mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <!-- Error message element for bank_name -->
                <p id="bank_name_error" class="text-sm text-red-500"></p>
            </div>
        </div>
    </div>
</div>
