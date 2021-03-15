<div x-data="{open: false}">
    <div class="flex items-center">
        <div class="flex items-center bg-gray-100 border border-gray-300 border-solid rounded-full cursor-pointer justify-items-center w-11 h-11" x-on:click="open = true">
            @if ($icon)
            <div class="w-full pt-1 text-center text-blue-500">
                <i class="material-icons">{{ $icon }}</i>
            </div>
            @endif
        </div>
        <div class="text-3xl cursor-pointer material-icons" x-on:click="open = true">arrow_drop_down</div>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center w-full overflow-hidden main-modal animated fadeIn faster" style="background: rgba(0,0,0,.7);"
        x-show="open === true">
		<div class="z-50 w-1/2 mx-auto bg-white border border-blue-500 shadow-lg modal-container md:max-w-11/12 rounded-xl"  @click.away="open = false">
			<div class="px-6 py-4 text-center modal-content">
				<!--Title-->
				<div class="flex items-center justify-between pb-3">
					<p class="text-2xl font-semibold text-gray-400">Icons</p>
					<div class="z-50 cursor-pointer modal-close" x-on:click="open = false">
						<svg class="text-gray-500 fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
							viewBox="0 0 18 18">
							<path
								d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
							</path>
						</svg>
					</div>
				</div>
				<!--Body-->
                <div class="p-2">
                    <div class="bg-gray-100 border border-gray-200 border-solid rounded-lg">
                        <input type="text" class="w-full px-3 py-2 bg-transparent browser-default" placeholder="Rechercher une icône..." wire:model="query" wire:keyup="search()">
                    </div>
                </div>


				<div class="my-6 overflow-y-auto" style="height: 400px">
                    <div class="grid w-full grid-cols-4 gap-4">
                        @forelse ($displayedIcons as $icon)
                            <div class="flex flex-col p-3 cursor-pointer hover:text-blue-500" wire:click="selectIcon('{{ $icon }}')" x-on:click="open = false">
                                <i class="material-icons">{{ $icon }}</i>
                                <span class="text-sm text-gray-400">{{ $icon }}</span>
                            </div>
                        @empty
                            <div class="col-span-4 font-semibold text-center text-red-500">
                                No result
                            </div>
                        @endforelse
                    </div>
				</div>
				<!--Footer-->
				{{-- <div class="flex justify-end pt-2 space-x-14">
					<button
						class="p-3 px-4 font-semibold text-black bg-gray-200 rounded hover:bg-gray-300" onclick="modalClose('main-modal')">Cancel</button>
					<button
						class="p-3 px-4 ml-3 text-white bg-blue-500 rounded-lg hover:bg-teal-400" onclick="validate_form(document.getElementById('add_caretaker_form'))">Confirm</button>
				</div> --}}
			</div>
		</div>
	</div>

</div>
