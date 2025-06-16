<!-- store-header.blade.php -->
<div class="mt-5 w-full max-w-6xl mx-auto bg-gray-100 rounded border border-gray-200">
    <!-- Header -->
    <div class="relative border-black rounded-2xl">
        <div class="max-w-screen-xl mx-auto">
            <div class="relative group">
                <img src="{{ asset('storage/' . $storeHeader) }}" class="w-full"
                     alt="Header Image" style="object-fit: cover; height: 170px;">
                <div class="absolute inset-0 bg-black flex items-center justify-center opacity-0 group-hover:opacity-75 hover:transition-opacity duration-300">
                    <div class="text-white text-center p-4">
                        <h1 class="text-3xl font-semibold mb-3">{{ $commercialNameEn }}</h1>
                        <p class="text-sm">{{ $description }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex mt-2 space-x-4 items-center p-4">
            <img src="{{ asset('storage/' . $logo) }}" class="w-32 h-32 rounded-full"
                 alt="Profile Photo">
            <div>
                <h2 class="text-2xl font-semibold px-2">{{ $commercialNameEn }}</h2>
                <p class="px-2">{{  $shortNameEn  }}</p>
                <p class="px-2">{{'#'.$storeId }}</p>
            </div>
        </div>
    </div>
</div>
