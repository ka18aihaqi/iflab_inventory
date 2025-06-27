@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3 justify-center">
        <div class="w-full md:w-9/12 px-3 pt-0">

            <!-- Error Notification -->
            @if (session('error'))
                <div id="error-alert" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm font-medium text-center transition-opacity duration-500">
                    {!! session('error') !!}
                </div>

                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('error-alert');
                        if (alert) {
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 4000);
                </script>
            @endif

            <div class="relative flex flex-col min-w-0 mb-6 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded bg-clip-border">
                <div class="p-6 pb-2 mb-0 bg-gradient-to-r from-blue-700 to-yellow-400 border-b-0 border-b-solid rounded border-b-transparent flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Back Icon -->
                        <a href="{{ route('allocates.index', ['location' => $allocateHardware->location_id]) }}" class="text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" title="Back">
                            <i class="fas fa-arrow-left mb-2"></i>
                        </a>
                        <h6 class="text-lg font-semibold text-white">Hardware</h6>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-4 max-w-xl mx-auto">
                        <form action="{{ route('allocates.hardware.update', $allocateHardware->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="flex gap-x-6 mb-4">
                                <!-- Location -->
                                <div class="w-1/2 px-1">
                                    <label for="location_id" class="block text-sm font-medium text-slate-700">Location <span class="text-red-500">*</span></label>
                                    <select id="location_id" name="location_id" required
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="" disabled>Choose a location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ $allocateHardware->location_id == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Desk Number -->
                                <div class="w-1/2 px-1">
                                    <label for="desk_number" class="block text-sm font-medium text-slate-700">Desk Number</label>
                                    <select id="desk_number" name="desk_number"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @for ($i = 1; $i <= 50; $i++)
                                            <option value="{{ $i }}" {{ $allocateHardware->desk_number == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Computer -->
                                <div class="w-1/2 px-1">
                                    <label for="computer_id" class="block text-sm font-medium text-slate-700">Computer</label>
                                    <select id="computer_id" name="computer_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($computers as $comp)
                                            <option value="{{ $comp->id }}" {{ $allocateHardware->computer_id == $comp->id ? 'selected' : '' }}>
                                                {{ $comp->name }} {{ $comp->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-x-6 mb-4">
                                <!-- Processor -->
                                <div class="w-1/2 px-1">
                                    <label for="processor_id" class="block text-sm font-medium text-slate-700">Processor</label>
                                    <select id="processor_id" name="processor_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($processors as $proc)
                                            <option value="{{ $proc->id }}" {{ $allocateHardware->processor_id == $proc->id ? 'selected' : '' }}>
                                                {{ $proc->name }} {{ $proc->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Disk Drive 1 -->
                                <div class="w-1/2 px-1">
                                    <label for="disk_drive_1_id" class="block text-sm font-medium text-slate-700">Disk Drive 1</label>
                                    <select id="disk_drive_1_id" name="disk_drive_1_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($diskDrives as $drive)
                                            <option value="{{ $drive->id }}" {{ $allocateHardware->disk_drive_1_id == $drive->id ? 'selected' : '' }}>
                                                {{ $drive->name }} {{ $drive->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Disk Drive 2 -->
                                <div class="w-1/2 px-1">
                                    <label for="disk_drive_2_id" class="block text-sm font-medium text-slate-700">Disk Drive 2</label>
                                    <select id="disk_drive_2_id" name="disk_drive_2_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($diskDrives as $drive)
                                            <option value="{{ $drive->id }}" {{ $allocateHardware->disk_drive_2_id == $drive->id ? 'selected' : '' }}>
                                                {{ $drive->name }} {{ $drive->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-x-6 mb-4">
                                <!-- VGA -->
                                <div class="w-1/2 px-1">
                                    <label for="vga_card_id" class="block text-sm font-medium text-slate-700">VGA Card</label>
                                    <select id="vga_card_id" name="vga_card_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($vgaCards as $vga)
                                            <option value="{{ $vga->id }}" {{ $allocateHardware->vga_card_id == $vga->id ? 'selected' : '' }}>
                                                {{ $vga->name }} {{ $vga->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- RAM -->
                                <div class="w-1/2 px-1">
                                    <label for="ram_id" class="block text-sm font-medium text-slate-700">RAM</label>
                                    <select id="ram_id" name="ram_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($rams as $ram)
                                            <option value="{{ $ram->id }}" {{ $allocateHardware->ram_id == $ram->id ? 'selected' : '' }}>
                                                {{ $ram->name }} {{ $ram->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Monitor -->
                                <div class="w-1/2 px-1">
                                    <label for="monitor_id" class="block text-sm font-medium text-slate-700">Monitor</label>
                                    <select id="monitor_id" name="monitor_id"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @foreach($monitors as $monitor)
                                            <option value="{{ $monitor->id }}" {{ $allocateHardware->monitor_id == $monitor->id ? 'selected' : '' }}>
                                                {{ $monitor->name }} {{ $monitor->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-x-6 mb-4">
                                <!-- Approximate Year -->
                                <div class="w-1/2 px-1">
                                    <label for="year_approx" class="block text-sm font-medium text-slate-700">Approximate Year</label>
                                    <select id="year_approx" name="year_approx"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        @for ($year = date('Y'); $year >= 2000; $year--)
                                            <option value="{{ $year }}" {{ $allocateHardware->year_approx == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- UPS Status -->
                                <div class="w-1/2 px-1">
                                    <label for="ups_status" class="block text-sm font-medium text-slate-700">UPS Status</label>
                                    <select id="ups_status" name="ups_status"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                        <option value="">-</option>
                                        <option value="Active" {{ $allocateHardware->ups_status === 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ $allocateHardware->ups_status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="pt-2 mt-4 flex justify-end">
                                <button type="submit" class="add-btn">
                                    Update
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection