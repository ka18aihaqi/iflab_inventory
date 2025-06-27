<div id="other-form" class="form-section">
    <div class="flex gap-x-6 mb-4">
        <!-- Name -->
        <div class="w-1/2 px-1">
            <label for="other-name" class="block text-sm font-medium text-slate-700">
                Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="other-name" name="name"
                value="{{ old('name', $inventory->name) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Whiteboard" required>
        </div>

        <!-- Stock -->
        <div class="w-1/2 px-1">
            <label for="other-stock" class="block text-sm font-medium text-slate-700">
                Stock <span class="text-red-500">*</span>
            </label>
            <input type="number" id="other-stock" name="stock" min="0" step="1"
                value="{{ old('stock', $inventory->stock) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 10">
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Description -->
        <div class="w-full px-1">
            <label for="other-description" class="block text-sm font-medium text-slate-700">
                Description
            </label>
            <input type="text" id="other-description" name="description"
                value="{{ old('description', $inventory->description) }}"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., type, brand, or any details">
        </div>
    </div>
</div>
