<div>
    <x-sidebars.sidebar>
        <!-- Sidebar header And Logo -->
        <x-sidebars.sidebar-header/>

        <x-sidebars.sidebar-links>

            <ul class="mt-3">
                <!-- Dashboard -->
                <x-sidebars.menu-item
                        routeName="dashboard"
                        :iconColor="Str::startsWith(Route::currentRouteName(), 'dashboard') ?  '#c8ff0d' : '#94a3b8'"
                        :isActive="Str::startsWith(Route::currentRouteName(), 'dashboard')"
                        icon="layout-dashboard"
                        translationKey="locale.sidebar.dashboard"
                />

                <!-- Store -->
                <x-sidebars.menu-item
                        routeName="store"
                        :iconColor="Route::currentRouteName() === 'store' ? '#c8ff0d' : '#94a3b8'"
                        :isActive="Route::currentRouteName() === 'store'"
                        icon="store"
                        translationKey="locale.sidebar.store"
                />

                @if ( \App\Models\Store::all()->where('owner_id', Auth::id())->count() != 0)
                    <!-- Rating -->
                    <x-sidebars.menu-item
                            routeName="rating.index"
                            :iconColor="Route::currentRouteName() === 'rating.index' ? '#c8ff0d' : '#94a3b8'"
                            :isActive="Route::currentRouteName() === 'rating.index'"
                            icon="star"
                            translationKey="locale.sidebar.rating"
                    />

                @endif

                <!-- Settings -->
                <x-sidebars.menu-item
                        routeName="profile.show"
                        :iconColor="Route::currentRouteName() === 'profile.show' ? '#c8ff0d' : '#94a3b8'"
                        :isActive="Route::currentRouteName() === 'profile.show'"
                        icon="settings"
                        translationKey="locale.sidebar.settings"
                />


                <!-- Support -->
                <x-sidebars.menu-item-with-sub-items
                        :routeName="in_array(Route::currentRouteName(), ['support.compliant', 'support.feedback'])"
                        :iconColor="in_array(Route::currentRouteName(),  ['support.compliant', 'support.feedback']) ? '#c8ff0d' : '#94a3b8'"
                        :hasSubItems="true"
                        :isActive="in_array(Route::currentRouteName(), ['support.compliant', 'support.feedback'])"
                        icon="headphones"
                        translationKey="locale.sidebar.support">

                    <x-sidebars.sub-menu-item
                            route="support.compliant"
                            :isActive="Route::is('support.compliant')"
                    >
                        @lang('locale.sidebar_sub.complaints')
                        </x-sidebar.sub-menu-item>

                        <x-sidebars.sub-menu-item
                                route="support.feedback"
                                :isActive="Route::is('support.feedback')"
                        >
                            @lang('locale.sidebar_sub.feedback')
                            </x-sidebar.sub-menu-item>


                            </x-sidebar.menu-item-with-sub-items>

                            <!-- About Us -->
                            <x-sidebars.menu-item
                                    routeName="landing-page"
                                    :iconColor="Str::startsWith(Route::currentRouteName(), 'landing-page') ?  '#c8ff0d' : '#94a3b8'"
                                    :isActive="Str::startsWith(Route::currentRouteName(), 'landing-page')"
                                    icon="rows-3"
                                    translationKey="locale.sidebar.about_us"
                            />

            </ul>
        </x-sidebars.sidebar-links>


        <!-- Expand / collapse button -->
        <x-sidebars.expand-collapse-button/>

    </x-sidebars.sidebar>
</div>
