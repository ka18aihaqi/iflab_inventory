<div id="ram-form" class="form-section">
    <div class="flex gap-x-6 mb-4">
        <!-- Brand -->
        <div class="w-1/2 px-1">
            <label for="ram-brand" class="block text-sm font-medium text-slate-700">
                Brand <span class="text-red-500">*</span>
            </label>
            <input type="text" id="ram-brand" name="brand"
                value="{{ old('brand', $inventory->name) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Kingston" required>
        </div>

        <!-- Size -->
        <div class="w-1/2 px-1">
            <label for="ram-size" class="block text-sm font-medium text-slate-700">
                Size <span class="text-red-500">*</span>
            </label>
            <div class="flex mt-1">
                <input type="number" id="ram-size" name="size" min="0" step="1"
                    value="{{ old('size', $inventory->size) }}"
                    class="rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800 w-full"
                    placeholder="e.g., 16">
                <span class="inline-flex items-center px-3 rounded-lg border border-l-0 border-slate-200 bg-gray-100 text-sm text-slate-600">
                    GB
                </span>
            </div>
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Type -->
        <div class="w-1/2 px-1">
            <label for="ram-type" class="block text-sm font-medium text-slate-700">
                Type <span class="text-red-500">*</span>
            </label>
            <select id="ram-type" name="type"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                <option value="" disabled {{ old('type', $inventory->type) ? '' : 'selected' }}>RAM Type</option>
                <option value="DDR3" {{ old('type', $inventory->type) == 'DDR3' ? 'selected' : '' }}>DDR3</option>
                <option value="DDR4" {{ old('type', $inventory->type) == 'DDR4' ? 'selected' : '' }}>DDR4</option>
                <option value="DDR5" {{ old('type', $inventory->type) == 'DDR5' ? 'selected' : '' }}>DDR5</option>
            </select>
        </div>

        <!-- Stock -->
        <div class="w-1/2 px-1">
            <label for="ram-stock" class="block text-sm font-medium text-slate-700">
                Stock <span class="text-red-500">*</span>
            </label>
            <input type="number" id="ram-stock" name="stock" min="0" step="1"
                value="{{ old('stock', $inventory->stock) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 10">
        </div>
    </div>
</div>
