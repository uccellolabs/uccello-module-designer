{{-- Card --}}
<div class="relative grid mb-11 justify-items-stretch">
    <div class="w-2/3 bg-white border border-gray-200 border-solid justify-self-center rounded-xl">
        <x-md-vertical-step-card-title title="{{ $title }}"></x-md-vertical-step-card-title>

        {{-- Content --}}
        {{ $slot }}

        {{ $after }}
    </div>
</div>
