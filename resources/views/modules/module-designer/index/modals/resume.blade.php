{{-- Icons modal --}}
<div id="resumeModal" class="modal" data-count="{{ $designedModules->count() }}">
    <div class="modal-content">
        <h5>
            <i class="material-icons primary-text left">replay</i>{{ uctrans('modal.resume.title', $module) }}
            <a href="javascript:void(0)" class="modal-action modal-close btn-flat close right"><i class="material-icons">close</i></a>
        </h5>

        <div class="row">
            @foreach ($designedModules as $designedModule)
                <div class="col s12">
                    <a href="#" data-structure='{{ json_encode($designedModule->data) }}'>
                        {{ $designedModule->name }}
                        @if ($designedModule->data->label ?? false) ({{ $designedModule->data->label }})@endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
