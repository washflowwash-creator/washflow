@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-xl bg-sky-100 px-3 py-2 text-sm font-semibold leading-5 text-sky-800 transition duration-150 ease-in-out'
            : 'inline-flex items-center rounded-xl px-3 py-2 text-sm font-medium leading-5 text-slate-600 hover:bg-sky-50 hover:text-sky-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
