<div>
    <?php
        $availableModules = collect();

        foreach ($modules as $_module) {
            if (auth()->user()->canRetrieve($domain, $_module)) {
                $_module->label = uctrans($_module->name, $_module);
                $availableModules[] = $_module;
            }
        }

        $availableModules = $availableModules->sortBy('label');

    ?>
    {{-- Module --}}
    <div class="input-field col s12 m6">
        <select id="field_module" data-param="module" data-container="body">
            @foreach ($availableModules as $_module)
                @continue(!auth()->user()->canRetrieve($domain, $_module))
                <option value="{{ $_module->name }}">{{ uctrans($_module->name, $_module) }}</option>
            @endforeach
        </select>
        <label for="field_module">{{ uctrans('field.uitype.entity.module', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.entity.module', $module) }}</small>
    </div>
</div>
