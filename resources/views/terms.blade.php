<x-guest-layout>
    @php
        $latestTerms = \App\Models\TermsCondition::latest('created_at')->first();
    @endphp

    @if($latestTerms)
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                    <div class="text-lg font-bold">
                        {{ __('locale.terms_conditions.title') }}
                    </div>
                    @if(app()->getLocale() === 'en')
                        {!! $latestTerms->body_en !!}
                    @else
                        {!! $latestTerms->body_ar !!}
                    @endif
                    @if($latestTerms->issued_at)
                        <p class="mt-4 text-sm text-gray-500">{{ __('locale.terms_conditions.last_updated') }} {{ $latestTerms->issued_at }}</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                    <div class="text-lg font-bold text-gray-500">
                        {{ __('locale.terms_conditions.not_available') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>
