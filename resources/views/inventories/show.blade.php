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

                    <div class="flex items-center justify-between mb-0">
                        <!-- Kiri: Back + Title -->
                        <div class="flex items-center space-x-3">
                            <!-- Back Icon -->
                            <a href="{{ route('inventories.index') }}" class="text-black hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" title="Back">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <h6 class="m-0">{{ $inventory->category }} - {{ $inventory->name }} {{ $inventory->description }}</h6>
                        </div>

                        <!-- Search -->
                        <form method="GET" action="{{ route('inventories.show', ['inventory' => $inventory->id]) }}" class="relative flex items-center transition-all rounded-lg ease-soft w-64">
                            <span class="text-sm ease-soft leading-5.6 absolute z-50 left-2 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                name="search"
                                value="{{ request('search') }}"
                                class="pl-8.75 text-sm focus:shadow-soft-blue-custom ease-soft w-full leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" 
                                placeholder="Type here..." 
                            />
                        </form>

                        <!-- Kanan: Add Button -->
                        <a href="{{ route('inventories.items.create', $inventory->id) }}" 
                        class="add-btn flex items-center text-sm text-green-500 hover:underline">
                            <i class="fas fa-plus text-sm mr-1"></i> Add
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Serial Number</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Condition</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Received Date</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Last Checked By</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Last Checked At</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status Allocation</th>
                                <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-tbody">
                            @forelse($items as $index => $item)
                            <tr>
                                <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $items->firstItem() + $index }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->serial_number }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->condition_status }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->received_date }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->lastCheckedBy?->username ?? '-' }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->last_checked_at }}</span>
                                </td>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 text-xs font-semibold leading-tight">{{ $item->status_allocate }}</p>
                                    @if($item->allocateHardware)
                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $item->allocateHardware->location->name ?? '-' }} Meja. {{ $item->allocateHardware->desk_number ?? '-' }}</p>
                                    @endif
                                    @if($item->allocateOther)
                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $item->allocateOther->location->name ?? '-' }}</p>
                                    @endif
                                </td>
                                <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <div class="flex justify-center items-center">
                                        {{-- <a href="{{ route('inventories.show', $item->id) }}" class="text-blue-500 hover:text-blue-800 text-base transition duration-200 transform hover:scale-110 mr-2" title="Show">
                                            <i class="fas fa-eye align-middle"></i>
                                        </a> --}}
                                        <a href="{{ route('inventories.items.edit', [$inventory->id, $item->id]) }}"
                                        class="text-yellow-500 hover:text-yellow-600 text-base transition duration-200 transform hover:scale-110 mr-2"
                                        title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('inventories.items.destroy', [$inventory->id, $item->id]) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete {{ $inventory->name }} {{ $inventory->description }} - {{ $item->serial_number }}?');" 
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-500 hover:text-red-600 text-base transition duration-200 transform hover:scale-110" 
                                                    title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-sm text-slate-400 italic text-center">No inventory found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($items->hasPages())
                        <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                            {{-- Previous Page Link --}}
                            @if ($items->onFirstPage())
                                <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                            @else
                                <a href="{{ $items->appends(['date' => request('date')])->previousPageUrl() }}"
                                class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                            @endif

                            {{-- Custom Pagination Elements --}}
                            @php
                                $currentPage = $items->currentPage();
                                $lastPage = $items->lastPage();
                                $start = max(1, $currentPage - 1);
                                $end = min($lastPage, $currentPage + 1);
                            @endphp

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                @else
                                    <a href="{{ $items->appends(['date' => request('date')])->url($page) }}"
                                    class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($items->hasMorePages())
                                <a href="{{ $items->appends(['date' => request('date')])->nextPageUrl() }}"
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