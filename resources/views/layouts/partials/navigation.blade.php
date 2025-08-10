<aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
    <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo-iflab.png') }}" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
            <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Informatics Lab</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

    <div class="items-center w-full h-full block overflow-auto">
        <ul class="flex flex-col pl-0 mb-0">

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-slot:icon>
                    <svg width="12" height="12" viewBox="0 0 45 40" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g fill="currentColor" fill-rule="nonzero">
                            <path class="opacity-60" d="M46.72 10.74L40.84.95A1.667 1.667 0 0039.17 0H7.83A1.667 1.667 0 006.16.95L.28 10.74a1.64 1.64 0 00-.28.99c0 4.32 3.49 7.83 7.8 7.84 1.93.01 3.8-.71 5.24-2.01 2.97 2.68 7.48 2.68 10.45 0 2.96 2.69 7.52 2.69 10.48 0a8.216 8.216 0 008.42-.67c2.83-1.26 4.65-4.07 4.64-7.16 0-.36-.1-.71-.28-1.01z"/>
                            <path d="M39.2 22.49c-1.82 0-3.62-.48-5.25-1.4a11.806 11.806 0 01-9.22.43 10.98 10.98 0 01-1.23-.58c-2.78 1.57-6 1.82-8.94.7a10.98 10.98 0 01-1.24-.58 10.365 10.365 0 01-5.24 1.31c-.65-.01-1.3-.07-1.94-.2v22.43a1.67 1.67 0 001.96 1.67h11.75V33.61h7.83v13.33h11.75a1.67 1.67 0 001.96-1.67V22.28c-.63.13-1.28.2-1.92.21z"/>
                        </g>
                    </svg>
                </x-slot:icon>
                Dashboard
            </x-nav-link>

            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Management pages</h6>
            </li>

            <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M192 0C86 0 0 86 0 192c0 77.41 96.63 186.9 146.87 241.34a48.38 48.38 0 0 0 70.26 0C287.37 378.9 384 269.41 384 192 384 86 298 0 192 0zm0 272a80 80 0 1 1 80-80 80.09 80.09 0 0 1-80 80z"/>
                    </svg>
                </x-slot:icon>
                Locations
            </x-nav-link>

            <x-nav-link :href="route('inventories.index')" :active="request()->routeIs('inventories.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M32 32C14.3 32 0 46.3 0 64v352c0 35.3 28.7 64 64 64h224V288H160V224h128v-64H160v-64h128V32H32zm576 160h-64v64h64v64h-64v64h64v64c0 35.3-28.7 64-64 64H352c-17.7 0-32-14.3-32-32V288h80v64h64v-64h-64v-64h64v-64h-64v-64h64V32h64c35.3 0 64 28.7 64 64v96c0 17.7-14.3 32-32 32z"/>
                    </svg>
                </x-slot:icon>
                Inventories
            </x-nav-link>

            <x-nav-link :href="route('allocates.index')" :active="request()->routeIs('allocates.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M528 0H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h160v32h-40c-13.3 0-24 10.7-24 24v16h288v-16c0-13.3-10.7-24-24-24h-40v-32h160c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM512 320H64V64h448v256z"/>
                    </svg>
                </x-slot:icon>
                Allocates
            </x-nav-link>

            <x-nav-link :href="route('transfers.index')" :active="request()->routeIs('transfers.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 10H6.83l3.58-3.59L9 5l-7 7 7 7 1.41-1.41L6.83 14H20v-2zm-9-5v2h8v2h-8v2l-3-3 3-3z"/>
                    </svg>
                </x-slot:icon>
                Transfers
            </x-nav-link>

            <x-nav-link :href="route('auditlogs.index')" :active="request()->routeIs('auditlogs.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 10H6.83l3.58-3.59L9 5l-7 7 7 7 1.41-1.41L6.83 14H20v-2zm-9-5v2h8v2h-8v2l-3-3 3-3z"/>
                    </svg>
                </x-slot:icon>
                Audit Logs
            </x-nav-link>

            <li class="w-full mt-4">
                <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase opacity-60">Account pages</h6>
            </li>

            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm89.6 32h-11.8c-22.2 10.3-46.9 16-72.8 16s-50.6-5.7-72.8-16h-11.8C65.3 288 0 353.3 0 432v16c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32v-16c0-78.7-65.3-144-144.4-144z"/>
                    </svg>
                </x-slot:icon>
                Users
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link :href="route('logout')" :active="false" onclick="event.preventDefault(); this.closest('form').submit();">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M502.6 273.4L400 376c-15.1 15.1-41 4.4-41-17v-72H208c-13.3 0-24-10.7-24-24v-16c0-13.3 10.7-24 24-24h151V152c0-21.4 25.9-32.1 41-17l102.6 102.6c9.4 9.4 9.4 24.6 0 34.8zM160 80c0-8.8-7.2-16-16-16H64c-17.7 0-32 14.3-32 32v320c0 17.7 14.3 32 32 32h80c8.8 0 16-7.2 16-16V368c0-8.8-7.2-16-16-16H96V160h48c8.8 0 16-7.2 16-16V80z"/>
                        </svg>
                    </x-slot:icon>
                    Logout
                </x-nav-link>
            </form>
        </ul>
    </div>
</aside>