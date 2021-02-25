{{-- Title --}}
<div class="p-3 pl-6 text-xl font-semibold border-b border-gray-200 border-solid cursor-pointer" wire:click="changeStep({{ $step }})">
    <span>{{ $title }}</span>
    <i class="float-right text-2xl material-icons">@if ($closed)expand_more @else expand_less @endif</i>
</div>
