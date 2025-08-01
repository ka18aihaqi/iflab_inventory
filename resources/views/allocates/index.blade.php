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
                            <a href="{{ route('allocates.create') }}" class="add-btn mr-2">
                                <i class="fas fa-plus text-sm mr-1"></i> Add
                            </a>
                            @if(request()->filled('location'))
                                <a href="{{ route('allocates.exportPdf', ['location' => request('location')]) }}" class="download-btn">
                                    <i class="fas fa-file-pdf text-sm mr-1"></i> PDF
                                </a>
                            @endif
                        </div>

                        <!-- Tengah: Filter Lokasi -->
                        <div class="flex justify-center w-1/3">
                            <form method="GET" action="{{ route('allocates.index') }}" class="flex items-center space-x-2">
                                <select name="location" class="filter-select" onchange="this.form.submit()">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <!-- Kanan: Search -->
                        <div class="flex justify-end w-1/3">
                            {{-- <form method="GET" action="{{ route('allocates.index') }}" class="relative flex flex-wrap items-stretch transition-all rounded-lg ease-soft">
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
                            </form> --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @if($selectedLocation)
        <!-- Allocated Hardware -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-2 mb-0 bg-gradient-to-r from-blue-700 to-yellow-400 border-b-0 border-b-solid rounded-t-2xl border-b-transparent text-center">
                        <h6 class="text-white">
                            Allocated Hardware
                        </h6>
                    </div>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">Desk No.</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Computer</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Disk Drive 1</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Disk Drive 2</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Processor</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">VGA</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">RAM</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Monitor</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Year Approx</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">UPS Status</th>
                                    <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">QR Code</th>
                                    <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocateHardwares as $index => $allocateHardware)
                                    <tr>
                                        <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateHardware->desk_number }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->computer->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->computer->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->diskDrive1->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->diskDrive1->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->diskDrive2->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->diskDrive2->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->processor->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->processor->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->vgaCard->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->vgaCard->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->ram->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->ram->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <p class="mb-0 text-xs font-semibold leading-tight">{{ $allocateHardware->monitor->name ?? '-' }}</p>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $allocateHardware->monitor->description ?? '-' }}</p>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateHardware->year_approx ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateHardware->ups_status ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <div class="flex justify-center items-center">
                                                <img src="{{ asset('storage/' . $allocateHardware->qr_code) }}" alt="QR Code" class="w-12 h-12">
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <div class="flex justify-center items-center">
                                                <!-- Show -->
                                                <a href="{{ route('allocates.hardware.show', $allocateHardware->id) }}"
                                                class="text-blue-500 hover:text-blue-800 text-base transition duration-200 transform hover:scale-110 mr-2"
                                                title="Show">
                                                    <i class="fas fa-eye align-middle"></i>
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('allocates.hardware.edit', $allocateHardware->id) }}" 
                                                    class="text-yellow-500 hover:text-yellow-600 text-base transition duration-200 transform hover:scale-110 mr-2" 
                                                    title="Edit">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form action="{{ route('allocates.hardware.destroy', $allocateHardware->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $allocateHardware->location->name }} No. Desk: {{ $allocateHardware->desk_number }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 text-base transition duration-200 transform hover:scale-110" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-sm text-slate-400 italic text-center">No allocated hardware found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($allocateHardwares->hasPages())
                            <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                                {{-- Previous Page Link --}}
                                @if ($allocateHardwares->onFirstPage())
                                    <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                                @else
                                    <a href="{{ $allocateHardwares->appends(['location' => request('location')])->previousPageUrl('hardware_page') }}"
                                        class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                                @endif

                                {{-- Custom Pagination Elements --}}
                                @php
                                    $currentPage = $allocateHardwares->currentPage();
                                    $lastPage = $allocateHardwares->lastPage();
                                    $start = max(1, $currentPage - 1);
                                    $end = min($lastPage, $currentPage + 1);
                                @endphp

                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $currentPage)
                                        <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                    @else
                                        <a href="{{ $allocateHardwares->appends(['location' => request('location')])->url($page, 'hardware_page') }}"
                                            class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($allocateHardwares->hasMorePages())
                                    <a href="{{ $allocateHardwares->appends(['location' => request('location')])->nextPageUrl('hardware_page') }}"
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
        <!-- Allocated Other Items -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-2 mb-0 bg-gradient-to-r from-blue-700 to-yellow-400 border-b-0 border-b-solid rounded-t-2xl border-b-transparent text-center">
                        <h6 class="text-white">
                            Allocated Other Items
                        </h6>
                    </div>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No.</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Item Name</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Description</th>
                                    <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Quantity</th>
                                    <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocateOthers as $index => $allocateOther)
                                    <tr>
                                        <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateOthers->firstItem() + $index }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateOther->others->name ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs leading-tight leading-tight text-slate-400">{{ $allocateOther->description ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">{{ $allocateOther->quantity ?? '-' }}</span>
                                        </td>
                                        <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                            <div class="flex justify-center items-center">
                                                <!-- Edit -->
                                                <a href="{{ route('allocates.other.edit', $allocateOther->id) }}" 
                                                    class="text-yellow-500 hover:text-yellow-600 text-base transition duration-200 transform hover:scale-110 mr-2" 
                                                    title="Edit">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form action="{{ route('allocates.other.destroy', $allocateOther->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $allocateOther->others->name }} in {{ $allocateOther->location->name }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 text-base transition duration-200 transform hover:scale-110" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-sm text-slate-400 italic text-center">No allocated other item found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($allocateOthers->hasPages())
                            <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                                {{-- Previous Page Link --}}
                                @if ($allocateOthers->onFirstPage())
                                    <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                                @else
                                    <a href="{{ $allocateOthers->appends(['location' => request('location')])->previousPageUrl('hardware_page') }}"
                                        class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                                @endif

                                {{-- Custom Pagination Elements --}}
                                @php
                                    $currentPage = $allocateOthers->currentPage();
                                    $lastPage = $allocateOthers->lastPage();
                                    $start = max(1, $currentPage - 1);
                                    $end = min($lastPage, $currentPage + 1);
                                @endphp

                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $currentPage)
                                        <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                    @else
                                        <a href="{{ $allocateOthers->appends(['location' => request('location')])->url($page, 'hardware_page') }}"
                                            class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($allocateOthers->hasMorePages())
                                    <a href="{{ $allocateOthers->appends(['location' => request('location')])->nextPageUrl('hardware_page') }}"
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
            <p class="text-sm text-gray-500 italic text-center">Please select a location to view data allocations.</p>
        </div>
    @endif
</div>
@endsection