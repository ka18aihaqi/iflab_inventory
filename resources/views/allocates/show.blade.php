@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3 justify-center">
        <div class="w-full sm:w-8/12 md:w-6/12 lg:w-5/12 max-w-full px-3 mt-6">
            <div class="relative flex flex-col h-full min-w-0 mb-6 break-words border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 px-4 bg-gradient-to-r from-blue-700 to-yellow-400 border-b-0 rounded-t-2xl">
                    <div class="flex flex-nowrap justify-center items-center space-x-6 text-center">
                        <a href="{{ route('allocates.index', ['location' => $allocateHardware->location_id]) }}" class="absolute left-4 top-6 text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 transition">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex items-center mr-4">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                            <h6 class="mb-0 text-white">{{ $allocateHardware->location->name ?? '-' }}</h6>
                        </div>
                        <div class="flex items-center">
                            <small class="text-white">No. Desk: {{ $allocateHardware->desk_number ?? '-' }}</small>
                        </div>
                    </div>
                </div>

                <div class="flex-auto pt-6 mb-4 space-y-4 max-w-4xl mx-auto">
                    <!-- Baris 1 -->
                    <div class="flex py-4 px-6 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-desktop text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">Computer</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->computer->name ?? '-' }} {{ $allocateHardware->computer->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-microchip text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">Processor</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->processor->name ?? '-' }} {{ $allocateHardware->processor->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2 (contoh lainnya) -->
                    <div class="flex py-4 px-6 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-hdd text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">Disk Drive 1</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->diskDrive1->name ?? '-' }} {{ $allocateHardware->diskDrive1->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-hdd text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">Disk Drive 2</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->diskDrive2->name ?? '-' }} {{ $allocateHardware->diskDrive2->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2 (contoh lainnya) -->
                    <div class="flex py-4 px-6 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-video text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">VGA</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->vgaCard->name ?? '-' }} {{ $allocateHardware->vgaCard->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-memory text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">RAM</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->ram->name ?? '-' }} {{ $allocateHardware->ram->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2 (contoh lainnya) -->
                    <div class="flex py-4 px-6 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center w-1/2">
                            <i class="fas fa-desktop text-red-700 text-base mr-4"></i>
                            <div class="flex flex-col">
                                <h6 class="mb-1 leading-normal text-sm text-slate-700">Monitor</h6>
                                <span class="leading-tight text-xs">
                                    {{ $allocateHardware->monitor->name ?? '-' }} {{ $allocateHardware->monitor->description ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(Auth::check())
                        <div class="flex justify-center mt-2">
                            <a href="{{ asset('storage/qrcodes/QR_TULT-06-01-1.png') }}"
                            class="download-btn bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold py-1.5 px-4 rounded-lg transition"
                            download>
                                <i class="fas fa-download mr-1"></i> Download QR
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection