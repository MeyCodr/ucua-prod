@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-1.5 rounded-full bg-indigo-50 text-sm font-semibold leading-5 text-indigo-700 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium leading-5 text-gray-500 hover:bg-gray-50 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
