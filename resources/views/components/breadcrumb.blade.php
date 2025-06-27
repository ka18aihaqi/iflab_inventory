@php
    use Illuminate\Support\Str;

    $currentRoute = Route::currentRouteName(); // contoh: "locations.index"
    $parts = explode('.', $currentRoute); // hasil: ['locations', 'index']

    // Ubah setiap bagian jadi capitalized
    $breadcrumbs = collect($parts)->map(function ($part) {
        return Str::title(str_replace('-', ' ', $part));
    });

    // Jika bagian terakhir "Index", jangan tampilkan
    if (Str::lower($breadcrumbs->last()) === 'index') {
        $breadcrumbs->pop();
    }
@endphp

<nav>
    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
        <li class="text-sm leading-normal">
            <a class="opacity-50 text-slate-700" href="{{ route('dashboard') }}">Pages</a>
        </li>

        @foreach ($breadcrumbs as $crumb)
            <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']">
                {{ $crumb }}
            </li>
        @endforeach
    </ol>

    <h6 class="mb-0 font-bold capitalize">{{ $breadcrumbs->last() }}</h6>
</nav>
