@props(['href', 'active'])

@php
    $linkClasses = $active
        ? 'group py-2.7 mb-2 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 bg-yellow-700 shadow-soft-xl transition-colors'
        : 'group py-2.7 mb-2 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 text-slate-700 hover:text-slate-700 hover:bg-yellow-700 transition-colors';
@endphp

<a {{ $attributes->merge(['href' => $href, 'class' => 'hover-parent ' . ($active ? 'is-active ' : '') . $linkClasses]) }}>
    <div class="shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg text-center xl:p-2.5 transition-all duration-300 icon-wrapper">
        <div class="flex items-center justify-center h-full icon-color">
            {{ $icon ?? '' }}
        </div>
    </div>
    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ $slot }}</span>
</a>
