@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 pt-0 mx-auto">
    <div class="flex flex-wrap my-6 -mx-3">
        <!-- Inventories -->
        <div class="w-full max-w-full px-3 mt-0 mb-6 md:mb-0 md:w-1/2 md:flex-none lg:w-1/2 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                <!-- Header -->
                <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
                    <div class="flex flex-wrap mt-0 -mx-3">
                        <div class="flex-none w-7/12 max-w-full px-3 mt-0 lg:w-1/2 lg:flex-none">
                            <h6>Hardwares</h6>
                            <p class="mb-0 text-sm leading-normal">
                            <i class="fas fa-chart-bar text-cyan-500"></i>
                            <span class="ml-1 font-semibold">{{ $averageAvailability }}% stock available</span>
                            overall
                            </p>
                        </div>
                        <div class="flex-none w-5/12 max-w-full px-3 my-auto text-right lg:w-1/2 lg:flex-none">
                            <div class="relative lg:float-right">
                                <a href="{{ route('inventories.index') }}" class="inline-flex items-center justify-center w-9 h-9 bg-white rounded-xl text-white hover:scale-105 transition-transform">
                                    <i class="fas fa-arrow-right text-black text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex-auto p-6 px-0 pb-2">
                    <div class="overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            @foreach ($stats as $stat)
                            <tbody>
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                        <div class="flex px-2 py-1">
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal">{{ $stat['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                                        <span class="text-xs font-semibold leading-tight"> {{ $stat['stock'] }} in stock / {{ $stat['allocated'] }} allocated </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                        <div class="w-3/4 mx-auto">
                                            <div>
                                                <div>
                                                    <span class="text-xs font-semibold leading-tight">{{ $stat['percent'] }}% stock available</span>
                                                </div>
                                            </div>
                                            <div
                                                class="duration-600 ease-soft bg-gradient-to-tl from-blue-600 to-cyan-400 -mt-0.38 -ml-px flex h-1.5 flex-col justify-center overflow-hidden whitespace-nowrap rounded bg-fuchsia-500 text-center text-white transition-all"
                                                role="progressbar"
                                                aria-valuenow="{{ $stat['percent'] }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                style="width: {{ $stat['percent'] }}%;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transfers -->
        <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none lg:w-1/2 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0 flex items-center justify-between">
                    <div>
                        <h6>Recent Transfers</h6>
                        <p class="text-sm leading-normal">
                            <i class="fa fa-arrow-up text-lime-500"></i>
                            <span class="font-semibold">{{ $dailyTransfers }}</span> transfers today
                        </p>
                    </div>
                    @php
                        $today = \Carbon\Carbon::now()->toDateString(); // Format: 'YYYY-MM-DD'
                    @endphp

                    <a href="{{ route('transfers.index', ['date' => $today]) }}" class="inline-flex items-center justify-center w-9 h-9 bg-white rounded-xl text-white hover:scale-105 transition-transform">
                        <i class="fas fa-arrow-right text-black text-lg"></i>
                    </a>
                </div>

                <div class="flex-auto p-4">
                    <div class="before:border-r-solid relative before:absolute before:top-0 before:left-4 before:h-full before:border-r-2 before:border-r-slate-100 before:content-[''] before:lg:-ml-px">
                        @foreach ($recentTransfers as $transfer)
                        <div class="relative mb-4 mt-0 after:clear-both after:table after:content-['']">
                            <div class="ml-11.252 pt-1.4 lg:max-w-120 relative -top-1.5 w-auto">
                                <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $transfer->item_name }}</h6>
                                <p class="mt-1 mb-0 text-xs font-semibold leading-tight text-slate-400">
                                    From <strong>{{ $transfer->from_location }}</strong> to <strong>{{ $transfer->to_location }}</strong>
                                    @if ($transfer->quantity > 1)
                                        ({{ $transfer->quantity }} items)
                                    @endif
                                    - {{ $transfer->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection