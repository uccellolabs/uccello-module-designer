<div class="h-10 bg-gray-100 rounded-lg @if($large)col-span-2 @else col-span-1 @endif">
    <div class="flex items-center h-full p-3">
        {{-- Color --}}
        <div class="rounded-full h-3 w-3 mr-2 {{ $color }}"></div>

        {{-- Label --}}
        <span class="flex-grow text-sm font-semibold">{{ $slot }}</span>

        {{-- Icons --}}
        <i class="text-base text-gray-600 material-icons">close</i>
    </div>
</div>
