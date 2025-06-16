<x-guest-layout>
    @php
        $latestPrivacyPolicy = \App\Models\PrivacyPolicy::latest('created_at')->first();
    @endphp

    @if($latestPrivacyPolicy)
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                    <div class="text-lg font-bold">
                        {{ __('locale.privacy_policy.title') }}
                    </div>
                    @if(app()->getLocale() === 'en')
                        {!! $latestPrivacyPolicy->body_en !!}
                    @else
                        {!! $latestPrivacyPolicy->body_ar !!}
                    @endif
                    @if($latestPrivacyPolicy->issued_at)
                        <p class="mt-4 text-sm text-gray-500">
                            {{ __('locale.privacy_policy.last_updated') }} {{ $latestPrivacyPolicy->issued_at }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                    <div class="text-lg font-bold text-gray-500">
                        {{ __('locale.privacy_policy.not_available') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>
