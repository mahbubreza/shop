@props(['name'])

@error($name)
    <p 
        class="text-xs font-semibold mt-1 
               text-red-600 dark:text-red-400">
        {{ $message }}
    </p>
@enderror
