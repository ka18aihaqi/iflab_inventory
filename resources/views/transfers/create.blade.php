@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 pt-0 mx-auto">
    <div class="flex flex-wrap -mx-3 justify-center">
        <div class="w-full md:w-9/12 px-3 pt-0">

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
                        <a href="{{ route('transfers.index') }}" class="text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" title="Back">
                            <i class="fas fa-arrow-left mb-2"></i>
                        </a>
                        <h6 class="text-lg font-semibold text-white">Transfer</h6>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-4 max-w-xl mx-auto">
                        <form action="{{ route('transfers.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Item Type -->
                            <div class="flex justify-center mb-4">
                                <div class="w-1/3 flex items-center space-x-4">
                                    <!-- Label -->
                                    <label for="type" class="w-1/3 text-sm font-medium text-slate-700">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <!-- Select -->
                                    <select name="item_type_id" required
                                        class="w-2/3 rounded-lg border border-slate-200 shadow-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none px-3 py-2 text-sm text-slate-800">
                                        <option value="" selected disabled>Choose type</option>
                                        <option value="hardware">Hardwares</option>
                                        <option value="other">Other Items</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Hardware Form -->
                            @include('transfers.partials.create-form-hardware')

                            <!-- Other Item Form -->
                            @include('transfers.partials.create-form-other')

                            @include('transfers.partials.script')

                            <!-- Submit -->
                            <div class="pt-2 mt-4 flex justify-end">
                                <button type="submit" class="add-btn">Submit Transfer</button>
                            </div>
                            <script>
                                const itemTypeSelect = document.querySelector('select[name="item_type_id"]');
                                const hardwareForm = document.getElementById('hardware-form');
                                const otherForm = document.getElementById('other-form');

                                function toggleForm(formToShow, formToHide) {
                                    formToShow.classList.remove('hidden');
                                    formToHide.classList.add('hidden');

                                    // Enable inputs in the form to show
                                    formToShow.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);

                                    // Disable inputs in the form to hide
                                    formToHide.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                }

                                itemTypeSelect.addEventListener('change', function () {
                                    const selected = this.value;

                                    if (selected === 'hardware') {
                                        toggleForm(hardwareForm, otherForm);
                                    } else if (selected === 'other') {
                                        toggleForm(otherForm, hardwareForm);
                                    } else {
                                        hardwareForm.classList.add('hidden');
                                        otherForm.classList.add('hidden');

                                        hardwareForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                        otherForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                    }
                                });

                                // Opsional: nonaktifkan semua saat load awal
                                window.addEventListener('DOMContentLoaded', () => {
                                    hardwareForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                    otherForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                });
                            </script>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection