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
                        <a href="{{ route('allocates.index', ['location' => $allocateOther->location_id]) }}" class="text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" title="Back">
                            <i class="fas fa-arrow-left mb-2"></i>
                        </a>
                        <h6 class="text-lg font-semibold text-white">Other Item</h6>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-4 max-w-xl mx-auto">
                        <form action="{{ route('allocates.other.update', $allocateOther->id) }}" method="POST" class="space-y-4">
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
                                            <option value="{{ $location->id }}" {{ $allocateOther->location_id == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Item -->
                                <div class="w-1/2 px-1">
                                    <label for="others_id" class="block text-sm font-medium text-slate-700">
                                        Item <span class="text-red-500">*</span>
                                    </label>
                                    <select id="others_id" name="others_id" required
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                                        <option value="" disabled>-</option>
                                        @foreach($others as $item)
                                            <option value="{{ $item->id }}" {{ $allocateOther->others_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} - Stock: {{ $item->stock }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-x-6 mb-4">
                                <!-- Quantity -->
                                <div class="w-1/4 px-1">
                                    <label for="quantity" class="block text-sm font-medium text-slate-700">Quantity</label>
                                    <input type="number" id="quantity" name="quantity" min="1"
                                        value="{{ old('quantity', $allocateOther->quantity) }}"
                                        placeholder="e.g. 5"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800" />
                                </div>

                                <!-- Description -->
                                <div class="w-3/4 px-1">
                                    <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                                    <input type="text" id="description" name="description"
                                        value="{{ old('description', $allocateOther->description) }}"
                                        placeholder="e.g. Wooden desk, 2 drawers"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800" />
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