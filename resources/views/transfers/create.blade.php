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
                            <div id="hardware-form">
                                @include('transfers.partials.create-form-hardware')
                            </div>

                            <!-- Other Item Form -->
                            <div id="other-form">
                                @include('transfers.partials.create-form-other')
                            </div>

                            @include('transfers.partials.script')

                            <!-- Submit -->
                            <div class="pt-2 mt-4 flex justify-end">
                                <button type="submit" class="add-btn">Submit Transfer</button>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const itemTypeSelect = document.querySelector('select[name="item_type_id"]');
                                    const hardwareForm = document.getElementById('hardware-form');
                                    const otherForm = document.getElementById('other-form');

                                    function toggleForms(selectedType) {
                                        if (selectedType === 'hardware') {
                                            hardwareForm.style.display = 'block';
                                            otherForm.style.display = 'none';

                                            // Aktifkan input di hardware, nonaktifkan di other
                                            hardwareForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
                                            otherForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                        } else if (selectedType === 'other') {
                                            hardwareForm.style.display = 'none';
                                            otherForm.style.display = 'block';

                                            // Aktifkan input di other, nonaktifkan di hardware
                                            hardwareForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                            otherForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
                                        } else {
                                            // Kalau belum pilih, sembunyikan keduanya dan disable input
                                            hardwareForm.style.display = 'none';
                                            otherForm.style.display = 'none';
                                            hardwareForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                            otherForm.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                                        }
                                    }

                                    // Event listener saat pilihan berubah
                                    itemTypeSelect.addEventListener('change', function () {
                                        toggleForms(this.value);
                                    });

                                    // Inisialisasi tampilan sesuai pilihan default (jika ada)
                                    toggleForms(itemTypeSelect.value);
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