<nav class="bg-white dark:bg-gray-900 border-b border-gray-300 dark:border-gray-700 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Liens principaux -->
            <div class="flex items-center space-x-8">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                            Dashboard
                        </a>
                    @endif
                @endauth

                <a href="{{ route('livres.index') }}"
                    class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    Acceuil
                </a>

                <a href="{{ route('livres.nouveautes') }}"
                    class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    Nouveautés
                </a>

                <a href="{{ route('livres.search.form') }}"
                    class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    Recherche
                </a>

                @auth
                    @if(!Auth::user()->isAdmin())
                        <a href="{{ route('panier.index') }}"
                            class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                            Mon Panier
                        </a>
                        <a href="{{ route('achats.historique') }}"
                            class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                            Historique d'achat
                        </a>

                        <a href="{{ route('messages.create') }}"
                            class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                            Contact
                        </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('messages.index') }}"
                            class="font-semibold text-black dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                            Messages
                        </a>
                    @endif

                @endauth

            </div>

            <!-- Dropdown utilisateur -->
            <div class="flex items-center space-x-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-3 py-2 rounded-md 
                               bg-blue-600 dark:bg-blue-400 
                               text-white dark:text-black 
                               hover:bg-blue-700 dark:hover:bg-blue-300 font-semibold shadow-md">
                        {{ Auth::check() ? Auth::user()->name : 'Invité' }}
                    </button>

                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg z-50">
                        @auth
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-black dark:text-white">
                                Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-black dark:text-white">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-center font-semibold text-black dark:text-white">
                                Se connecter
                            </a>
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-center font-semibold text-black dark:text-white">
                                S’inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>