<div id="monitor-form" class="form-section hidden">
    <div class="flex gap-x-6 mb-4">
        <!-- Brand -->
        <div class="w-1/2 px-1">
            <label for="monitor-brand" class="block text-sm font-medium text-slate-700">
                Brand <span class="text-red-500">*</span>
            </label>
            <input type="text" id="monitor-brand" name="brand"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Samsung" required>
        </div>

        <!-- Resolution -->
        <div class="w-1/2 px-1">
            <label for="monitor-resolution" class="block text-sm font-medium text-slate-700">
                Resolution <span class="text-red-500">*</span>
            </label>
            <input type="text" id="monitor-resolution" name="resolution"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 1920x1080">
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Inch -->
        <div class="w-1/2 px-1">
            <label for="monitor-inch" class="block text-sm font-medium text-slate-700">
                Inch <span class="text-red-500">*</span>
            </label>
            <div class="flex mt-1">
                <input type="number" id="monitor-inch" name="inch" min="1" step="0.1"
                    class="rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800 w-full"
                    placeholder="e.g., 24">
                <span class="inline-flex items-center px-3 rounded-lg border border-l-0 border-slate-200 bg-gray-100 text-sm text-slate-600">
                    ″
                </span>
            </div>
        </div>

        <!-- Stock -->
        <div class="w-1/2 px-1">
        </div>
    </div>
</div>