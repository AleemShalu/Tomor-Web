<x-app-admin-layout>
    <div class="p-6">
        <nav class="flex py-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{route('dashboard')}}"
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{route('admin.users.special-needs')}}"
                           class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Special
                            Needs Management
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span
                            class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">View Details</span>
                    </div>
                </li>
            </ol>
        </nav>


        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md px-6 py-8">
            <h1 class="text-3xl font-semibold mb-6">
                {{__('admin.user_management.users_list.user_info')}}
            </h1>

            <!-- Display User Information -->
            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium">
                    {{__('admin.user_management.users_list.name')}}

                </label>
                <p class="text-gray-800">{{ $user->name }}</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium">
                    {{__('admin.user_management.users_list.email')}}

                </label>
                <p class="text-gray-800">{{ $user->email }}</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium">
                    {{__('admin.user_management.special_needs.qualified_for_special_needs')}}

                </label>
                <p class="text-gray-800">
                    @if($user->customer_with_special_needs->special_needs_qualified == 1)
                        {{__('admin.user_management.special_needs.qualified')}}
                    @elseif($user->customer_with_special_needs->special_needs_qualified == 0)
                        {{__('admin.user_management.special_needs.not_qualified')}}
                    @else
                        <!-- Handle the case if the value is not 0 or 1 -->
                        {{__('admin.user_management.special_needs.unknown')}}
                    @endif
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium">
                    {{__('admin.user_management.special_needs.description_special_needs')}}
                </label>
                <p class="text-gray-800">{{ $user->customer_with_special_needs->special_needs_description }}</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-600 text-sm font-medium">
                    {{__('admin.user_management.special_needs.attachment')}}
                </label>
                <div>
                    @if ($user->customer_with_special_needs->special_needs_attachment)
                        {{--                        <a href="{{ $user->customer->special_needs_attachment }}" target="_blank" class="text-blue-500 underline">View Attachment</a>--}}
                        <button id="viewPdfButton"
                                class="mt-5  py-2 px-4 bg-black text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            Open PDF
                        </button>
                    @else
                        <p class="text-gray-500">
                            {{__('admin.user_management.special_needs.na')}}

                        </p>
                    @endif
                </div>
            </div>

            <!-- Add an iframe to display the PDF -->
            <div id="pdfContainer" class="hidden">
                <iframe id="pdfIframe" src="" frameborder="0" width="100%" height="600"></iframe>
                <button id="closePdfButton"
                        class="mt-4 py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    Close PDF
                </button>
            </div>


            <!-- Divider Line -->
            <hr class="my-8 border-t border-gray-300">

            <!-- Special Needs Qualified Dropdown -->
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-200 text-green-800 p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-200 text-red-800 p-4 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Form to update special needs information -->
            <form action="{{ route('admin.users.special-needs.update')}}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-6">
                    <label class="block text-gray-600 text-sm font-medium mb-2">
                        {{__('admin.user_management.special_needs.customer_qualified_or_not')}}
                    </label>
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                    <select id="customer_qualified" name="customer_qualified"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1"
                                @if($user->customer_with_special_needs->special_needs_qualified == 1) selected @endif>
                            {{__('admin.user_management.special_needs.qualified')}}
                        </option>
                        <option value="0"
                                @if($user->customer_with_special_needs->special_needs_qualified == 0) selected @endif>
                            {{__('admin.user_management.special_needs.not_qualified')}}
                        </option>
                    </select>
                </div>

                <!-- Save Changes Button -->
                <div class="mt-8">
                    <button type="submit"
                            class="py-3 px-6 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        {{__('admin.user_management.special_needs.save_change')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-admin-layout>
<script>
    document.getElementById('viewPdfButton').addEventListener('click', function () {
        // Get the PDF URL from the anchor tag
        var pdfPath = "{{ $user->customer_with_special_needs->special_needs_attachment }}";

        // Generate the full URL using the Storage::url() method
        var pdfUrl = "{{ Storage::url('') }}" + pdfPath;
        // Show the PDF container
        document.getElementById('pdfContainer').classList.remove('hidden');

        // Set the PDF URL as the source of the iframe
        document.getElementById('pdfIframe').setAttribute('src', pdfUrl);
    });

    document.getElementById('closePdfButton').addEventListener('click', function () {
        // Hide the PDF container and reset the iframe source
        document.getElementById('pdfContainer').classList.add('hidden');
        document.getElementById('pdfIframe').setAttribute('src', '');
    });
</script>
