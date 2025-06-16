<x-authentication-layout>
    <h1 class="text-3xl text-slate-800 font-bold mb-6">{{ __('locale.greeting.welcome_back') }} ✨</h1>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <!-- Form -->
    <form method="POST" action="{{ route('login.owner.submit') }}">
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
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            @endif
            <x-jet-button class="ml-3">
                {{ __('Log in') }}
            </x-jet-button>
        </div>
        <br>
        <div>
            <a href="{{ url('auth/google') }}" class="login-with-google-btn">
                {{ __('locale.auth.login.sign_in_google') }}
            </a>
        </div>
    </form>
    <x-jet-validation-errors class="mt-4"/>
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-slate-200">
        <div class="text-sm">
            {{ __('locale.auth.login.dont_have_account') }} <a class="font-medium text-indigo-500 hover:text-indigo-600"
                                                               href="{{ route('register') }}">{{ __('Register') }}</a>
        </div>
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
</x-authentication-layout>

<style>
    .login-with-google-btn {
        transition: background-color .3s, box-shadow .3s;
        padding: 12px 16px 12px 42px;
        border: none;
        border-radius: 3px;
        box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
        color: #757575;
        font-size: 14px;
        font-weight: 500;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
        background-color: white;
        background-repeat: no-repeat;
        background-position: 12px 11px;
    }

    .login-with-google-btn:hover {
        box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
    }

    .login-with-google-btn:active {
        background-color: #eeeeee;
    }

    .login-with-google-btn:focus {
        outline: none;
        box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25), 0 0 0 3px #c8dafc;
    }

    .login-with-google-btn:disabled {
        filter: grayscale(100%);
        background-color: #ebebeb;
        box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
        cursor: not-allowed;
    }

    body {
        text-align: center;
        padding-top: 2rem;
    }
</style>
