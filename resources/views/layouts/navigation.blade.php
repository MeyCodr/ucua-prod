<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    {{--
        The compiled public/css/app.css on this install predates this markup and can't
        currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
        handful of Tailwind utilities below were never generated. These rules backfill
        exactly those classes with their standard Tailwind values.
    --}}
    <style>
        .gap-3 { gap: 0.75rem; }
        .w-7 { width: 1.75rem; }
        .h-7 { height: 1.75rem; }
        .py-1\.5 { padding-top: 0.375rem; padding-bottom: 0.375rem; }
        .pl-2 { padding-left: 0.5rem; }
        .pr-3 { padding-right: 0.75rem; }
        .space-y-1 > * + * { margin-top: 0.25rem; }

        /* Desktop links need ~1024px; below that everything goes in the hamburger menu. */
        .nav-wide { display: none !important; }
        @media (min-width: 1024px) {
            .nav-wide { display: flex !important; }
            .nav-narrow { display: none !important; }
        }
    </style>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo" style="height: 28px; width: auto; display: block;">
                    </a>
                </div>

                <div class="nav-wide flex-shrink-0" style="width: 1px; height: 24px; background: #e5e7eb; margin-left: 24px;"></div>

                <!-- Navigation Links -->
                <div class="nav-wide gap-1 items-center" style="margin-left: 1.5rem;">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{-- {{ __('Dashboard') }} --}} Home
                    </x-nav-link>

                    <x-nav-link :href="route('ShowListTickets', ['category' => 'Pending'])" :active="request()->routeIs('ShowListTickets')">
                        Tickets
                    </x-nav-link>

                    @if (Auth::user()?->isAdmin() || Auth::user()?->she_admin())
                        <x-nav-link :href="route('admin.redeem.list', ['status' => 'Pending'])" :active="request()->routeIs('admin.redeem.list', ['status' => 'Pending'])">
                            Redemption Requests
                        </x-nav-link>

                        <x-nav-link :href="route('ShowAllSubmissions')" :active="request()->routeIs('ShowAllSubmissions')">
                            All Tickets
                        </x-nav-link>

                        <x-nav-link :href="route('Division.index')" :active="request()->routeIs('Division.index')">
                            Divisions & Departments
                        </x-nav-link>

                        <x-nav-link :href="route('User.index')" :active="request()->routeIs('User.index')">
                            Users
                        </x-nav-link>

                        <x-nav-link :href="route('ShowExportPage')" :active="request()->routeIs('ShowExportPage')">
                            Export
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="nav-wide items-center" style="margin-left: 1.5rem;">
                @php
                    $userNameParts = array_filter(preg_split('/\s+/', trim(Auth::user()->name)));
                    $userInitials = mb_strtoupper(implode('', array_map(fn ($p) => mb_substr($p, 0, 1), array_slice($userNameParts, 0, 2)))) ?: '?';
                @endphp
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full text-sm font-medium text-gray-600 hover:bg-gray-50 focus:outline-none transition duration-150 ease-in-out">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold flex-shrink-0">
                                {{ $userInitials }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="nav-narrow -mr-2 flex items-center">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="nav-narrow hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Home
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('ShowListTickets', ['category' => 'Pending'])" :active="request()->routeIs('ShowListTickets')">
                Tickets
            </x-responsive-nav-link>

            @if (Auth::user()?->isAdmin() || Auth::user()?->she_admin())
                <x-responsive-nav-link :href="route('admin.redeem.list', ['status' => 'Pending'])" :active="request()->routeIs('admin.redeem.list')">
                    Redemption Requests
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('ShowAllSubmissions')" :active="request()->routeIs('ShowAllSubmissions')">
                    All Tickets
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('Division.index')" :active="request()->routeIs('Division.index')">
                    Divisions & Departments
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('User.index')" :active="request()->routeIs('User.index')">
                    Users
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('ShowExportPage')" :active="request()->routeIs('ShowExportPage')">
                    Export
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
