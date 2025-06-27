@if(!$search || $diskDrives->total())
    <div class="w-full lg:w-1/2 px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6>Disk Drives</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="w-[50px] px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                                <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Name</th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Stock</th>
                                <th class="px-2 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($diskDrives as $index => $item)
                                <tr>
                                    <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $diskDrives->firstItem() + $index }}</span>
                                    </td>

                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-xs font-semibold leading-tight">{{ $item->name ?? '-' }}</p>
                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $item->description ?? '-' }}</p>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $item->stock ?? '-' }}</span>
                                    </td>
                                    <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex justify-center items-center">
                                            <!-- Edit -->
                                            <a href="{{ route('inventories.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-600 text-base transition duration-200 transform hover:scale-110 mr-2" title="Edit">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $item->name }}?');" class="inline">
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
                                    <td colspan="12" class="text-sm text-slate-400 italic text-center">No disk drives found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($diskDrives->hasPages())
                        <nav class="flex items-center justify-center space-x-1 mt-4 text-sm">
                            {{-- Previous Page Link --}}
                            @if ($diskDrives->onFirstPage())
                                <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&lt;</span>
                            @else
                                <a href="{{ $diskDrives->previousPageUrl() }}" class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-1 transition duration-200 transform hover:scale-105">&lt;</a>
                            @endif

                            {{-- Custom Pagination Elements --}}
                            @php
                                $currentPage = $diskDrives->currentPage();
                                $lastPage = $diskDrives->lastPage();
                                $start = max(1, $currentPage - 1);
                                $end = min($lastPage, $currentPage + 1);
                            @endphp

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-2 py-0.5 bg-yellow-500 text-white rounded mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</span>
                                @else
                                    <a href="{{ $diskDrives->url($page) }}" class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">{{ $page }}</a>
                                @endif
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($diskDrives->hasMorePages())
                                <a href="{{ $diskDrives->nextPageUrl() }}" class="px-2 py-0.5 border rounded hover:bg-gray-100 mx-2 transition duration-200 transform hover:scale-105">&gt;</a>
                            @else
                                <span class="px-2 py-0.5 border rounded text-gray-400 mx-2 transition duration-200 transform">&gt;</span>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif