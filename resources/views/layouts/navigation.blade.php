<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-stone-200/80 bg-white/85 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-10 w-10 text-indigo-700" />
                        <span class="hidden text-sm font-bold text-stone-900 lg:block">MARTE</span>
                    </a>
                </div>

                <div class="hidden space-x-5 sm:-my-px sm:ms-8 sm:flex">
                    @include('layouts.partials.role-nav-links')
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <span class="me-4 hidden rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600 lg:inline">
                    {{ Auth::user()->role->label() }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-md border border-stone-200 bg-white px-3 py-2 text-sm font-semibold leading-4 text-stone-600 shadow-sm transition hover:border-indigo-200 hover:text-stone-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Mi perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md border border-stone-200 bg-white p-2 text-stone-500 shadow-sm transition hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-stone-200 bg-white/95 sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @include('layouts.partials.role-nav-links-responsive')
        </div>

        <div class="pt-4 pb-1 border-t border-stone-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="text-xs text-gray-400">{{ Auth::user()->role->label() }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Mi perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
