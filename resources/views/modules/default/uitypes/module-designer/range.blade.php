<div>
    {{-- Min value --}}
    <div class="input-field col s12 m3">
        <input id="field_min" type="number" data-param="min" value="0">
        <label for="field_min" class="required active">{{ uctrans('field.uitype.range.min', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.range.min', $module) }}</small>
    </div>

    {{-- Max value --}}
    <div class="input-field col s12 m3">
        <input id="field_max" type="number" data-param="max" value="100">
        <label for="field_max" class="required active">{{ uctrans('field.uitype.range.max', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.range.max', $module) }}</small>
    </div>

    {{-- Step --}}
    <div class="input-field col s12 m4">
        <input id="field_step" type="number" data-param="step" value="1" min="0">
        <label for="field_step" class="required active">{{ uctrans('field.uitype.range.step', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.range.step', $module) }}</small>
    </div>

    {{-- Repeated --}}
    <div class="input-field col s12 m2">
        <p style="margin-top: 15px; margin-bottom: 15px">
            <label>
                <input id="field_repeated" type="checkbox" data-param="repeated" value="true" />
                <span>{{ uctrans('field.uitype.range.repeated', $module) }}</span>
            </label>
        </p>
        <small class="help primary-text">{{ uctrans('info.uitype.range.repeated', $module) }}</small>
    </div>
</div>
