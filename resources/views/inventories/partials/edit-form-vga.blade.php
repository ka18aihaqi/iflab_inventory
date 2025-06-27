<div id="vga-form" class="form-section">
    <div class="flex gap-x-6 mb-4">
        <!-- Brand -->
        <div class="w-1/2 px-1">
            <label for="vga-brand" class="block text-sm font-medium text-slate-700">
                Brand <span class="text-red-500">*</span>
            </label>
            <input type="text" id="vga-brand" name="brand"
                value="{{ old('brand', $inventory->name) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., NVIDIA" required>
        </div>

        <!-- Size -->
        <div class="w-1/2 px-1">
            <label for="vga-size" class="block text-sm font-medium text-slate-700">
                Size <span class="text-red-500">*</span>
            </label>
            <div class="flex mt-1">
                <input type="number" id="vga-size" name="size" min="0" step="1"
                    value="{{ old('size', $inventory->size) }}"
                    class="rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800 w-full"
                    placeholder="e.g., 8">
                <span class="inline-flex items-center px-3 rounded-lg border border-l-0 border-slate-200 bg-gray-100 text-sm text-slate-600">
                    GB
                </span>
            </div>
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Stock -->
        <div class="w-1/2 px-1">
            <label for="vga_stock" class="block text-sm font-medium text-slate-700">
                Stock <span class="text-red-500">*</span>
            </label>
            <input type="number" id="vga_stock" name="stock" min="0" step="1"
                value="{{ old('stock', $inventory->stock) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 10">
        </div>

        <!-- Kosong -->
        <div class="w-1/2 px-1"></div>
    </div>
</div>
