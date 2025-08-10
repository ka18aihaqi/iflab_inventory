@extends('layouts.app')
@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3 justify-center">
        <div class="w-full sm:w-8/12 md:w-6/12 lg:w-5/12 max-w-full px-3 mt-6">

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
                        <a href="{{ route('inventories.index') }}" class="text-white hover:text-slate-800 text-base transition-transform transform hover:scale-110 mr-4" title="Back">
                            <i class="fas fa-arrow-left mb-2"></i>
                        </a>
                        <h6 class="text-lg font-semibold text-white">{{ $inventory->category }}</h6>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-2 pb-2">
                    <div class="p-4 max-w-xl mx-auto">
                        <form action="{{ route('inventories.update', $inventory->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Form Khusus Berdasarkan Item Type -->
                            @switch($inventory->category)
                                @case('Computer')
                                    @include('inventories.partials.edit-form-computer', ['inventory' => $inventory])
                                    @break

                                @case('Disk Drive')
                                    @include('inventories.partials.edit-form-drives', ['inventory' => $inventory])
                                    @break

                                @case('Processor')
                                    @include('inventories.partials.edit-form-processors', ['inventory' => $inventory])
                                    @break

                                @case('VGA')
                                    @include('inventories.partials.edit-form-vga', ['inventory' => $inventory])
                                    @break

                                @case('RAM')
                                    @include('inventories.partials.edit-form-ram', ['inventory' => $inventory])
                                    @break

                                @case('Monitor')
                                    @include('inventories.partials.edit-form-monitors', ['inventory' => $inventory])
                                    @break

                                @default
                                    @include('inventories.partials.edit-form-others', ['inventory' => $inventory])
                            @endswitch

                            <!-- Computer Form -->

                            <!-- Drive Drive Form) -->

                            <!-- Processor Form -->

                            <!-- VGA Form -->

                            <!-- RAM Form -->

                            <!-- Monitor Form -->

                            <!-- Other Items Form -->

                            <!-- Submit -->
                            <div class="pt-2 mt-4 flex justify-end">
                                <button type="submit" class="add-btn">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection