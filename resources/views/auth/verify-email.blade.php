<x-authentication-layout>
    <h1 class="text-3xl text-slate-800 font-bold mb-6">{{ __('locale.verify-email.title') }}</h1>
    <div>
        {{ __('locale.verify-email.message') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('locale.verify-email.verification-link-sent') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <x-jet-button type="submit">
                    {{ __('locale.verify-email.resend-button') }}
                </x-jet-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="ml-1">
                <button type="submit" class="text-sm underline hover:no-underline">
                    {{ __('locale.verify-email.logout-button') }}
                </button>
            </div>
        </form>
    </div>
</x-authentication-layout>
