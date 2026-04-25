@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl border border-sky-200 bg-sky-100 px-3 py-2 text-start text-base font-medium text-sky-800 transition duration-150 ease-in-out'
            : 'block w-full rounded-xl border border-transparent px-3 py-2 text-start text-base font-medium text-slate-600 hover:bg-sky-50 hover:text-sky-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
