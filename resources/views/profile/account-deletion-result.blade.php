<x-guest-layout>
    <div class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm md:max-w-md text-center">
            <div class="flex justify-center">
                @if($status === 'success')
                    <div>
                        <h2 class="text-2xl font-bold text-green-600">{{__('locale.common.success')}}</h2>
                        <p class="mt-2 text-gray-600">{{ $message }}</p>
                        <div class="flex justify-center mt-6">
                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                @else
                    <div>
                        <h2 class="text-2xl font-bold text-red-600">{{__('locale.common.errors.error')}}</h2>
                        <p class="mt-2 text-gray-600">{{ $message }}</p>
                        <div class="flex justify-center mt-6">
                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                @endif
            </div>
            <a href="/"
               class="inline-block mt-8 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300 shadow">
                {{__('locale.common.go_to_homepage')}}
            </a>
        </div>
    </div>
</x-guest-layout>
