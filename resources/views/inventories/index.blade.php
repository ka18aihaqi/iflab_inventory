@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 pt-0 mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    
                    <!-- Success Notification -->
                    @if (session('success'))
                        <div id="success-alert" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm font-medium text-center transition-opacity duration-500">
                            {!! session('success') !!}
                        </div>

                        <script>
                            setTimeout(() => {
                                const alert = document.getElementById('success-alert');
                                if (alert) {
                                    alert.style.opacity = '0';
                                    setTimeout(() => alert.remove(), 500); // Remove from DOM after fade
                                }
                            }, 4000); // hilang setelah 4 detik
                        </script>
                    @endif

                    <div class="flex items-center justify-between">
                        <!-- Add Button -->
                        <a href="{{ route('inventories.create') }}" class="add-btn">
                            <i class="fas fa-plus text-sm mr-1"></i> Add
                        </a>

                        <!-- Search -->
                        <form method="GET" action="{{ route('inventories.index') }}" class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
                            <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                class="pl-8.75 text-sm focus:shadow-soft-blue-custom ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" 
                                placeholder="Type here..." 
                            />
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="flex flex-wrap -mx-3 gap-4">
        @php
            $search = request('search');
        @endphp

        <!-- Computer Table -->
        @include('inventories.partials.table-computer')

        <!-- Disk Drives Table -->
        @include('inventories.partials.table-drives')

        <!-- Processors Table -->
        @include('inventories.partials.table-processors')

        <!-- VGA Table -->
        @include('inventories.partials.table-vga')

        <!-- RAM Table -->
        @include('inventories.partials.table-ram')

        <!-- Monitors Table -->
        @include('inventories.partials.table-monitors')

        <!-- Other Items Table -->
        @include('inventories.partials.table-others')

    </div>

</div>
@endsection