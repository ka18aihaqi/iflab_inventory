<!-- Hardware Form -->
<div id="hardware-form">
    <div class="flex gap-x-6 mb-4">
        <!-- Lokasi Asal -->
        <div class="w-1/4 px-1">
            <label for="from_location" class="block text-sm font-medium text-slate-700">
                From Location <span class="text-red-500">*</span>
            </label>
            <select id="from_location_hardware" name="from_location" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" disabled selected>Choose location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- From Desk Number -->
        <div class="w-1/4 px-1">
            <label for="from_desk_number" class="block text-sm font-medium text-slate-700">
                From Desk Number <span class="text-red-500">*</span>
            </label>
            <select id="from_desk_number" name="from_desk_number" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" selected disabled>Select desk</option>
                {{-- Options akan diisi via JavaScript --}}
            </select>
        </div>

        <!-- To Location -->
        <div class="w-1/4 px-1">
            <label for="to_location" class="block text-sm font-medium text-slate-700">
                To Location <span class="text-red-500">*</span>
            </label>
            <select id="to_location" name="to_location" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" disabled selected>Choose location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- To Desk Number -->
        <div class="w-1/4 px-1">
            <label for="to_desk_number" class="block text-sm font-medium text-slate-700">
                To Desk Number <span class="text-red-500">*</span>
            </label>
            <select id="to_desk_number" name="to_desk_number" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" selected disabled>Select desk</option>
                {{-- Options akan diisi via JavaScript --}}
            </select>
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Component Hardware From Location -->
        <div class="w-1/2 px-1">
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Select Components (From) <span class="text-red-500">*</span>
            </label>
            <div id="hardware_components_from" class="flex flex-col gap-2 text-sm text-slate-800">
                <p class="text-slate-500 italic col-span-2">Please select a desk number first.</p>
            </div>
        </div>

        <!-- Component Hardware To Location -->
        <div class="w-1/2 px-1">
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Components (To)
            </label>
            <div id="hardware_components_to" class="flex flex-col gap-2 text-sm text-slate-800">
                <p class="text-slate-500 italic col-span-2">Please select a desk number first.</p>
            </div>
        </div>
    </div>

    <!-- Note -->
    <div class="px-1">
        <label for="note" class="block text-sm font-medium text-slate-700">Note</label>
        <textarea name="note" id="note" rows="3"
            class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
            placeholder="Optional notes about the transfer..."></textarea>
    </div>
</div>