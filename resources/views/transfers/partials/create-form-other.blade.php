<!-- Other Item Form -->
<div id="other-form">
    <div class="flex gap-x-6 mb-4">
        <!-- Lokasi Asal -->
        <div class="w-1/2 px-1">
            <label for="from_location" class="block text-sm font-medium text-slate-700">
                From Location <span class="text-red-500">*</span>
            </label>
            <select id="from_location_other" name="from_location" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" disabled selected>Choose location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- To Location -->
        <div class="w-1/2 px-1">
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
    </div>

    <div class="flex gap-x-6 mb-4">
        <div class="w-1/2 px-1">
            <!-- Item (Only for Other Items) -->
            <label for="other_item" class="block text-sm font-medium text-slate-700">
                Item <span class="text-red-500">*</span>
            </label>
            <select id="other_item" name="other_item" required
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" disabled selected>Select item</option>
            </select>

            <!-- Quantity -->
            <label for="quantity" class="block text-sm font-medium text-slate-700">
                Quantity
            </label>
            <input type="number" name="quantity" id="quantity" min="1" placeholder="e.g. 3"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800" />
        </div>

        <!-- Component Hardware To Location -->
        <div class="w-1/2 px-1">
            <label for="note" class="block text-sm font-medium text-slate-700">Note</label>
            <textarea name="note" id="note" rows="4"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="Optional notes about the transfer..."></textarea>
        </div>
    </div>
</div>