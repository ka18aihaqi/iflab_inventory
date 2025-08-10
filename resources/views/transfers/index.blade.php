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

                    <div class="flex items-center justify-between w-full">
                        <!-- Kiri: Add + PDF -->
                        <div class="flex items-center space-x-2 w-1/3">
                            <a href="{{ route('transfers.create') }}" class="add-btn mr-2">
                                <i class="fas fa-plus text-sm mr-1"></i> Add
                            </a>
                            @if(request('date'))
                                <a href="{{ route('transfers.exportPdf', ['date' => request('date')]) }}" class="download-btn">
                                    <i class="fas fa-file-pdf text-sm mr-1"></i> PDF
                                </a>
                            @endif
                        </div>

                        <!-- Tengah: Filter Tanggal -->
                        <div class="flex justify-center w-1/3">
                            <form method="GET" action="{{ route('transfers.index') }}" class="flex items-center space-x-2">
                                <input type="date" name="date" value="{{ request('date') }}" class="filter-select" onchange="this.form.submit()" />
                            </form>
                        </div>

                        <!-- Kosong -->
                        <div class="flex justify-end w-1/3">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if(request('date'))
        <!-- Transfer Logs Table -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6>Transfer Logs</h6>
                    </div>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Item Name</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">From Location</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">To Location</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Note</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Transferred By</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Transferred Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transferLogs as $index => $transferLog)
                                    <tr>
                                        <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLogs->firstItem() + $index }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $transferLog->item->inventory->name }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $transferLog->item->inventory->description }} - SN: {{ $transferLog->item->serial_number }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLog->from_location }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLog->to_location }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLog->note }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLog->transferredBy->username }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $transferLog->transferredBy->created_at }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-sm text-slate-400 italic text-center">No transfer logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($transferLogs->hasPages())
                            <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                                {{-- Previous Page Link --}}
                                @if ($transferLogs->onFirstPage())
                                    <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                                @else
                                    <a href="{{ $transferLogs->appends(['date' => request('date')])->previousPageUrl() }}"
                                    class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                                @endif

                                {{-- Custom Pagination Elements --}}
                                @php
                                    $currentPage = $transferLogs->currentPage();
                                    $lastPage = $transferLogs->lastPage();
                                    $start = max(1, $currentPage - 1);
                                    $end = min($lastPage, $currentPage + 1);
                                @endphp

                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $currentPage)
                                        <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                    @else
                                        <a href="{{ $transferLogs->appends(['date' => request('date')])->url($page) }}"
                                        class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($transferLogs->hasMorePages())
                                    <a href="{{ $transferLogs->appends(['date' => request('date')])->nextPageUrl() }}"
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
    @else
        <div class="flex items-center justify-center">
            <p class="text-sm text-gray-500 italic text-center">Please select a date to view transfer logs.</p>
        </div>
    @endif
</div>
@endsection