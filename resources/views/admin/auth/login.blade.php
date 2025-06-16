<x-admin-authentication-layout>
    <h1 class="text-3xl text-slate-800 font-bold mb-6">{{ __('admin.login.welcome') }}</h1>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <!-- Form -->
    <form method="POST" action="{{ route('login.admin.submit') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" type="email" name="email" :value="old('email')" required autofocus/>
            </div>
            <div>
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" type="password" name="password" required autocomplete="current-password"/>
            </div>
        </div>
        <div class="flex items-center justify-center mt-6">
            <x-jet-button class="ml-3">
                {{ __('Log in') }}
            </x-jet-button>
        </div>
    </form>
    <x-jet-validation-errors class="mt-4"/>
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200">
        <div class="mt-10">
            <form action="{{ route('setLanguage') }}" method="POST" class="flex items-center justify-center space-x-2">
                @csrf
                <label for="locale" class="hidden">Select Language</label>
                <div class="relative">
                    <select name="locale" id="locale" onchange="this.form.submit()"
                            class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-sm font-medium text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English (UK)</option>
                        <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية (السعودية)
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 8l4 4 4-4H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div>
                    @if(app()->getLocale() === 'ar')
                        <img src="{{asset('images/flags/sa.jpg')}}" alt="Saudi Arabia"
                             class="w-6 h-4 ">
                    @elseif(app()->getLocale() === 'en')
                        <img src="{{asset('/images/flags/uk.png')}}" alt="United Kingdom"
                             class="w-6 h-4 rounded-sm">
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-admin-authentication-layout>


<style>
    body {
        text-align: center;
        padding-top: 2rem;
    }
</style>
