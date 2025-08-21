@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3 justify-center">
        <div class="w-full sm:w-8/12 md:w-6/12 lg:w-5/12 max-w-full px-3 mt-6">

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
                        <a href="{{ route('inventories.show', $inventory->id) }}" 
                        class="text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" 
                        title="Back">
                            <i class="fas fa-arrow-left mb-2"></i>
                        </a>
                        <h6 class="text-lg font-semibold text-white">{{ $inventory->name }} {{ $inventory->description }}</h6>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-4 max-w-xl mx-auto">
                        <form action="{{ route('inventories.items.update', [$inventory->id, $item->id]) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            {{-- Hidden: inventory_id --}}
                            <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">

                            <div class="flex gap-x-6 mb-4">
                                {{-- Serial Number --}}
                                <div class="w-1/2 px-1">
                                    <label for="serial_number" class="block text-sm font-medium text-slate-700">
                                        Serial Number
                                    </label>
                                    <input type="text" id="serial_number" name="serial_number"
                                        value="{{ old('serial_number', $item->serial_number) }}"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                                        placeholder="e.g., SN12345XYZ">
                                </div>

                                {{-- Kondisi Status --}}
                                <div class="w-1/2 px-1">
                                    <label for="condition_status" class="block text-sm font-medium text-slate-700">
                                        Condition Status
                                    </label>
                                    <select id="condition_status" name="condition_status"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                                        <option value="Baik" {{ old('condition_status', $item->condition_status) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Perlu Perbaikan" {{ old('condition_status', $item->condition_status) == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                        <option value="Rusak" {{ old('condition_status', $item->condition_status) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex gap-x-6 mb-4">
                                {{-- Received Date --}}
                                <div class="w-1/2 px-1">
                                    <label for="received_date" class="block text-sm font-medium text-slate-700">
                                        Tanggal Barang Masuk
                                    </label>
                                    <input type="date" id="received_date" name="received_date"
                                        value="{{ old('received_date', $item->received_date ? \Carbon\Carbon::parse($item->received_date)->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                                        required>
                                </div>
                            </div>

                            {{-- Submit --}}
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