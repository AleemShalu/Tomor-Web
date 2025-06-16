<!-- resources/views/components/store-create-form.blade.php -->
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto dark:bg-gray-900 dark:text-white">
    <section class="bg-white dark:bg-gray-800 p-5 rounded-xl">
        <x-store-create.stepper/>
        @if($errors->any())
            <x-store-create.alert :errors="$errors"/>
        @endif
        <form id="stepped-form" method="POST" action="{{ route('store.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-12">
                <x-store-create.store-information/>
                <x-store-create.commercial-information :businesses="$businesses" :countries="$countries"/>
                <x-store-create.bank-information/>
                <x-store-create.contact-information/>
            </div>
            <x-store-create.navigation-buttons/>
        </form>
    </section>
</div>
