<nav x-data="{ open: false }" class="bg-white shadow-sm border-bottom">

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between py-2 items-center">

            {{-- Logo --}}
            <a href="{{ route('welcome') }}">
                <x-application-logo class="h-8 w-auto content-center "/>
            </a>


            {{-- Main Links --}}

            <div class="hidden md:flex space-x-4 w-full">


                <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    <i class="bi bi-house-door me-1"></i> {{ __('Inicio') }}
                </x-nav-link>
                <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                    <i class="bi bi-newspaper me-1"></i> {{ __('Novedades') }}
                </x-nav-link>

                @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="bi bi-speedometer2 me-1"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('movements.index')" :active="request()->routeIs('movements.*')">
                        <i class="bi bi-wallet2 me-1"></i> {{ __('Movimientos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        <i class="bi bi-tags me-1"></i> {{ __('Categorías') }}
                    </x-nav-link>
                @endauth

            </div>


            {{-- User Dropdown --}}
            <div class="flex items-center gap-4">
                @auth
                    <div class="hidden md:flex flex-col items-center gap-3">

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center gap-2 px-4 py-2 bg-light border border-gray-200 rounded-md shadow-sm hover:bg-gray-100 transition text-sm font-medium text-gray-700">
                                    <span>{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->isPro())
                                        <i class="bi bi-star-fill text-yellow-500" title="Plan PRO"></i>
                                    @endif
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.2l3.71-3.97a.75.75 0 011.08 1.04l-4.25 4.54a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    <i class="bi bi-person-circle me-2"></i> {{ __('Mi Perfil') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Cerrar sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>

                        @if(Auth::user()->isAdmin())
                            <x-nav-link class="text-red-700" :href="route('admin.dashboard')"
                                        :active="request()->routeIs('admin.*')">
                                <i class="bi bi-gear-fill me-1"></i> {{ __('Admin Panel') }}
                            </x-nav-link>
                        @endif

                    </div>
                @endauth


            </div>

            {{-- Mobile Menu Button --}}
            <div class="md:hidden flex items-center">
                @auth
                    @if(Auth::user()->isAdmin())
                        <x-nav-link class="text-red-700" :href="route('admin.dashboard')"
                                    :active="request()->routeIs('admin.*')">
                            <i class="bi bi-gear-fill me-1"></i> {{ __('Admin Panel') }}
                        </x-nav-link>
                    @endif
                @endauth
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>

        {{-- Responsive Menu --}}
        <div :class="{ 'block': open, 'hidden': !open }" class="md:hidden hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    <i class="bi bi-house-door me-1"></i> {{ __('Inicio') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')">
                    <i class="bi bi-newspaper me-1"></i> {{ __('Novedades') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="bi bi-speedometer2 me-1"></i> {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('movements.index')" :active="request()->routeIs('movements.*')">
                    <i class="bi bi-wallet2 me-1"></i> {{ __('Movimientos') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    <i class="bi bi-tags me-1"></i> {{ __('Categorías') }}
                </x-responsive-nav-link>


            </div>
            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4 text-gray-700">
                        <div class="font-medium">
                            {{ Auth::user()->name }}
                            @if(Auth::user()->isPro())
                                <i class="bi bi-star-fill text-yellow-500" title="Plan PRO"></i>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            <i class="bi bi-person-circle me-2"></i> {{ __('Mi Perfil') }}
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('Cerrar sesión') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>


    </div>


</nav>
