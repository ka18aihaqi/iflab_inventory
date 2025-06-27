<div id="computer-form" class="form-section">
    <div class="flex gap-x-6 mb-4">
        <!-- Brand -->
        <div class="w-1/2 px-1">
            <label for="computer-brand" class="block text-sm font-medium text-slate-700">
                Brand <span class="text-red-500">*</span>
            </label>
            <input type="text" id="computer-brand" name="brand"
                value="{{ old('brand', $inventory->name) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Asus" required>
        </div>

        <!-- Model -->
        <div class="w-1/2 px-1">
            <label for="computer-model" class="block text-sm font-medium text-slate-700">
                Model
            </label>
            <input type="text" id="computer-model" name="model"
                value="{{ old('model', $inventory->description) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Vivobook 14">
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Stock -->
        <div class="w-1/2 px-1">
            <label for="computer-stock" class="block text-sm font-medium text-slate-700">
                Stock <span class="text-red-500">*</span>
            </label>
            <input type="number" id="computer-stock" name="stock" min="0" step="1"
                value="{{ old('stock', $inventory->stock) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 10">
        </div>

        <!-- Empty Form -->
        <div class="w-1/2 px-1"></div>
    </div>
</div>