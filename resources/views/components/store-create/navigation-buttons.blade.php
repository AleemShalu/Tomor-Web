<!-- Buttons for cancel, previous, and next -->
<div class="mt-6 flex items-center justify-end gap-x-6">
    <button type="button" onclick="goBack()"
            class="text-sm font-semibold leading-6 text-gray-900">{{ __('locale.button.cancel') }}</button>
    <button type="button" onclick="prevStep()" id="prev_button" name="prev_button"
            class="rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-600 shadow-sm hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-300">
        {{ __('locale.button.back') }}
    </button>
    <button type="button" onclick="nextStep()" id="next_button"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ __('locale.button.verify_and_next') }}
    </button>
    <button id="save_button" type="submit"
            class="hidden rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            style="display: none;">
        {{ __('locale.button.save') }}
    </button>