<select {{$attributes->merge(['class'=>'col-start-1 row-start-1 w-full appearance-none rounded-md
    bg-white text-gray-900 outline outline-1 outline-gray-300
    py-1.5 pr-8 pl-3 text-base sm:text-sm/6
    focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600
    dark:bg-gray-800 dark:text-gray-100 dark:outline-gray-600 dark:focus:outline-indigo-400'])}}>
    {{$slot}}                        
</select>
<svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4">
<path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
</svg>