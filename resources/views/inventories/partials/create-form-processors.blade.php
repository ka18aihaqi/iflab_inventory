<div id="processor-form" class="form-section hidden">
    <div class="flex gap-x-6 mb-4">
        <!-- Type -->
        <div class="w-1/2 px-1">
            <label for="processor-type" class="block text-sm font-medium text-slate-700">
                Type <span class="text-red-500">*</span>
            </label>
            <input type="text" id="processor-type" name="type"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Intel, AMD" required>
        </div>

        <!-- Model -->
        <div class="w-1/2 px-1">
            <label for="processor-model" class="block text-sm font-medium text-slate-700">
                Model <span class="text-red-500">*</span>
            </label>
            <input type="text" id="processor-model" name="model"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., Ryzen 5 5600G">
        </div>
    </div>

    <div class="flex gap-x-6 mb-4">
        <!-- Frequency -->
        <div class="w-1/2 px-1">
            <label for="processor-frequency" class="block text-sm font-medium text-slate-700">
                Frequency <span class="text-red-500">*</span>
            </label>
            <div class="flex mt-1">
                <input type="number" step="0.1" min="0" id="processor-frequency" name="frequency"
                    class="rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800 w-full"
                    placeholder="e.g., 3.9">
                <span class="inline-flex items-center px-3 rounded-lg border border-l-0 border-slate-200 bg-gray-100 text-sm text-slate-600">
                    GHz
                </span>
            </div>
        </div>


        <!-- Stock -->
        <div class="w-1/2 px-1">
            <label for="processor-stock" class="block text-sm font-medium text-slate-700">
                Stock <span class="text-red-500">*</span>
            </label>
            <input type="number" id="processor-stock" name="stock"
                class="mt-1 block w-full rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800"
                placeholder="e.g., 10" min="0" step="1">
        </div>
    </div>
</div>