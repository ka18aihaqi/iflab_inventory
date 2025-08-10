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

                    <div class="flex items-center justify-between mb-4">
                        <!-- Add Button -->
                        <a href="{{ route('inventories.create') }}" class="add-btn">
                            <i class="fas fa-plus text-sm mr-1"></i> Add
                        </a>

                        <!-- Filter Category -->
                        <select 
                            id="category-filter"
                            class="text-sm rounded-lg border border-gray-300 bg-white py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>

                        <!-- Search -->
                        <input 
                            type="text" 
                            id="search-input"
                            class="pl-3 text-sm rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder-gray-500 focus:border-blue-500 focus:outline-none" 
                            placeholder="Type here..." 
                        />
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6>Inventories</h6>
                </div>

                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Category</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Name</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Quantity</th>
                                <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-tbody">
                            @include('inventories.partials.table-body', ['inventories' => $inventories])
                        </tbody>
                    </table>
                    @if ($inventories->hasPages())
                        <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                            {{-- Previous Page Link --}}
                            @if ($inventories->onFirstPage())
                                <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                            @else
                                <a href="{{ $inventories->appends(['date' => request('date')])->previousPageUrl() }}"
                                class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                            @endif

                            {{-- Custom Pagination Elements --}}
                            @php
                                $currentPage = $inventories->currentPage();
                                $lastPage = $inventories->lastPage();
                                $start = max(1, $currentPage - 1);
                                $end = min($lastPage, $currentPage + 1);
                            @endphp

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                @else
                                    <a href="{{ $inventories->appends(['date' => request('date')])->url($page) }}"
                                    class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($inventories->hasMorePages())
                                <a href="{{ $inventories->appends(['date' => request('date')])->nextPageUrl() }}"
                                class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">&gt;</a>
                            @else
                                <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&gt;</span>
                            @endif
                        </nav>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection