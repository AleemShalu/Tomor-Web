<!-- store-information.blade.php -->
<div class="mt-10 w-full max-w-6xl mx-auto bg-gray-300 border border-gray-200 rounded">
    <h1 class="text-2xl font-bold p-4">
        {{--        <i class="fas fa-store mr-2"></i>--}}
        <dt class="mb-2 font-bold">{{ __('locale.navigation.store_information') }}</dt>
    </h1>
    <div class="bg-gray-100 dark:bg-gray-800 text-center" id="statistics" role="tabpanel"
         aria-labelledby="statistics-tab">
        <dl class="sm:flex flex-row max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-4">

            <div class="basis-1/4 md:basis-1/3 grow">
                <dt class="mb-2 font-bold">{{ __('locale.commercial.commercial_registration_number') }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">{{ $commercialRegistrationNo }}</dd>
            </div>
            <div class="basis-1/4 md:basis-1/3">
                <dt class="mb-2 font-bold">{{ __('locale.contact.contact_number') }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">{{ $contactNo }}</dd>
            </div>
            <div class="basis-1/4 md:basis-1/3">
                <dt class="mb-2 font-bold">{{ __('locale.tax.tax_id_number') }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">{{ $taxIdNumber }}</dd>
            </div>
        </dl>
        <dl class="sm:flex flex-row max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
            <div class="basis-1/4 md:basis-1/3">
                <dt class="mb-2 font-bold">{{ __('locale.commercial.commercial_registration_expiry') }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">{{ $commercialRegistrationExpiry }}</dd>
            </div>
            <div class="basis-1/4 md:basis-1/3">
                <dt class="mb-2 font-bold">{{ __('locale.commercial.municipal_license_number') }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">{{ $municipalLicenseNo }}</dd>
            </div>
            <div class="basis-1/4 md:basis-1/3 grow">
                <dt class="mb-2 font-bold">{{ __('locale.store.store_status') }}</dt>
                @if($status == 0)
                    <dd class="text-gray-500 dark:text-gray-400">{{ __('locale.store.not_active') }}</dd>
                @else
                    <dd class="text-gray-500 dark:text-gray-400">{{ __('locale.store.active') }}</dd>
                @endif
            </div>
        </dl>
    </div>
</div>
