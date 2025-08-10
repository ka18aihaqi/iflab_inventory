@extends('layouts.app')
@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans font-semibold leading-normal text-sm text-gray-600">Last Updated Location</p>
                                    <h5 class="mb-0 font-bold text-gray-900">
                                        {{ $lastUpdatedLocation ? $lastUpdatedLocation->name : 'No Data' }}
                                        @if ($lastUpdatedLocation)
                                        <span class="leading-normal text-sm font-weight-bolder text-lime-500">
                                            {{ $lastUpdatedLocation->updated_at->diffForHumans() }}
                                        </span>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                    <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans font-semibold leading-normal text-sm text-gray-600">Last Updated Inventory</p>
                                    <h5 class="mb-0 font-bold text-gray-900">
                                        {{ $lastUpdatedInventory ? $lastUpdatedInventory->inventory->name ?? 'Unknown Item' : 'No Data' }}
                                        @if ($lastUpdatedInventory)
                                        <span class="leading-normal text-sm font-weight-bolder text-lime-500">
                                            {{ $lastUpdatedInventory->updated_at->diffForHumans() }}
                                        </span>
                                        @endif
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1">
                                        SN: {{ $lastUpdatedInventory->serial_number ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-cyan-400">
                                    <i class="ni leading-none ni-box-2 text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans font-semibold leading-normal text-sm text-gray-600">Last Allocate Item</p>
                                    <h5 class="mb-0 font-bold text-gray-900">
                                        @if ($lastAllocate)
                                            @php
                                                $itemName = null;
                                                if (method_exists($lastAllocate, 'inventory')) {
                                                    $itemName = $lastAllocate->inventory->name ?? null;
                                                } else {
                                                    // For AllocateHardware, coba ambil komputer (computer_id) atau salah satu komponennya
                                                    $itemName = 'Allocated Hardware';
                                                }
                                            @endphp
                                            {{ $itemName ?? 'Unknown Item' }}
                                            <span class="leading-normal text-sm font-weight-bolder text-lime-500">
                                                {{ $lastAllocate->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            No Data
                                        @endif
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if ($lastAllocate)
                                            ID: {{ $lastAllocate->id }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                                    <i class="ni leading-none ni-send text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans font-semibold leading-normal text-sm text-gray-600">Last Transfer Item</p>
                                    <h5 class="mb-0 font-bold text-gray-900">
                                        @if ($lastTransfer)
                                            {{ $lastTransfer->item->inventory->name ?? 'Unknown Item' }}
                                            <span class="leading-normal text-sm font-weight-bolder text-lime-500">
                                                {{ $lastTransfer->created_at->diffForHumans() }}
                                            </span>
                                        @else
                                            No Data
                                        @endif
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1">
                                        From: {{ $lastTransfer->from_location ?? '-' }}<br>
                                        To: {{ $lastTransfer->to_location ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-yellow-600 to-orange-400">
                                    <i class="ni leading-none ni-repeat text-lg relative top-3.5 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap mt-6 -mx-3">
            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-5/12 lg:flex-none">
                <div class="border-black/12.5 shadow-soft-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                    <div class="flex-auto p-4">
                        <h6 class="mt-6 mb-0 ml-2 font-semibold text-lg">Capacity Overview</h6>
                        <p class="ml-2 leading-normal text-sm mb-6">Per location capacity usage status</p>
                        <div class="w-full px-6 mx-auto max-w-screen-2xl rounded-xl">
                            <div class="flex flex-wrap mt-0 -mx-3">
                                @foreach ($locations as $loc)
                                <div class="flex-none w-1/4 max-w-full py-4 pl-0 pr-3 mt-0">
                                    <div class="flex mb-2">
                                        <div class="flex items-center justify-center w-5 h-5 mr-2 text-center bg-center rounded fill-current shadow-soft-2xl bg-gradient-to-tl from-purple-700 to-pink-500 text-neutral-900">
                                            <!-- Contoh icon dokumen, bisa diganti sesuai kebutuhan -->
                                            <svg width="10px" height="10px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <title>document</title>
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                        <g transform="translate(1716.000000, 291.000000)">
                                                            <g transform="translate(154.000000, 300.000000)">
                                                                <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                                                                <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <p class="mt-1 mb-0 font-semibold leading-tight text-xs">{{ $loc->name ?? 'Unnamed Location' }}</p>
                                    </div>
                                    <h4 class="font-bold">{{ $loc->used_capacity }} / {{ $loc->capacity_limit }}</h4>
                                    <div class="text-xs h-0.75 flex w-3/4 overflow-visible rounded-lg bg-gray-200">
                                        <div
                                            class="duration-600 ease-soft -mt-0.38 -ml-px flex h-1.5 w-{{ floor($loc->capacity_percent * 3 / 4) }}/100 flex-col justify-center overflow-hidden whitespace-nowrap rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500 text-center text-white transition-all"
                                            role="progressbar"
                                            aria-valuenow="{{ $loc->capacity_percent }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ $loc->capacity_percent }}%;">
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $loc->capacity_percent }}%
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    @foreach ($conditionStats as $condition)
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans font-semibold leading-normal text-sm text-gray-600">
                                        Condition Status
                                    </p>
                                    <h5 class="mb-0 font-bold text-gray-900">
                                        {{ $condition->condition_status ?? 'Unknown' }}
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Total: {{ $condition->total }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-green-600 to-teal-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection