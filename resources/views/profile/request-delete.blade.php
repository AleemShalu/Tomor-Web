<x-guest-layout>
<div class="  mx-auto mt-10 min-w-fit border border-gray-200 bg-white p-8 rounded shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">{{__('locale.gest.delete.request_account_deletion')}}</h2>

    @if(session('message'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form class=" mb-4" action="{{ url('/request-delete') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium">{{__('locale.gest.delete.email_eddress')}}</label>
            <input type="email" name="email" id="email" class="border rounded w-full p-2 mt-2" required>
            @if($errors->has('email'))
                <span class="text-red-500 text-sm">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="text-xl font-bold mt-2">
            {{ __('locale.gest.privcy.agreement_of_account_deletion') }}
        </div>
        <div class=" flex flex-col gap-2 p-2">
            <div class=" text-sm ">
                {{ __('locale.gest.privcy.agreement_of_account_deletion_description') }}
            </div>
            <ul class="text-sm flex flex-col gap-2">
                <li>
                    <span class="font-bold">{{ __('locale.gest.privcy.account_deletion_title') }}: </span>
                    {{ __('locale.gest.privcy.account_deletion') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('locale.gest.privcy.data_retention_title') }}: </span>
                    {{ __('locale.gest.privcy.data_retention') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('locale.gest.privcy.no_restoration_title') }}: </span>
                    {{ __('locale.gest.privcy.no_restoration') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('locale.gest.privcy.legal_compliance_title') }}: </span>
                    {{ __('locale.gest.privcy.legal_compliance') }}
                </li>
            </ul>
            <div class="font-bold">
                ⚠️ {{ __('locale.gest.privcy.acknowledgment') }}
            </div>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ __('locale.gest.delete.send_reletion_request') }}
        </button>
    </form>
</div>
</x-guest-layout>
