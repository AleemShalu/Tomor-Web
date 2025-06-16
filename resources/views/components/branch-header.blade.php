<div class="border-b border-gray-200 dark:border-gray-700 bg-white">
    <div class="text-2xl font-bold py-3 px-3 ml-3">
        <a href="{{ route('branch.manage', ['id' => $branch->store->id]) }}" class="">{{ $branch->store->store_name() }}</a>
        >
        {{ __('locale.branch_dashboard.branch_store') }}
    </div>
    <x-branch-navigation :branch="$branch"></x-branch-navigation>
</div>
