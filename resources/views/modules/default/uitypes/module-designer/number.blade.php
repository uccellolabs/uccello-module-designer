<div>
    {{-- Min value --}}
    <div class="input-field col s12 m3">
        <input id="field_min" type="number" data-param="min">
        <label for="field_min" class="required">{{ uctrans('field.uitype.number.min', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.number.min', $module) }}</small>
    </div>

    {{-- Max value --}}
    <div class="input-field col s12 m3">
        <input id="field_max" type="number" data-param="max">
        <label for="field_max" class="required">{{ uctrans('field.uitype.number.max', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.number.max', $module) }}</small>
    </div>

    {{-- Step --}}
    <div class="input-field col s12 m4">
        <input id="field_step" type="number" data-param="step" value="1" min="0">
        <label for="field_step" class="active">{{ uctrans('field.uitype.number.step', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.number.step', $module) }}</small>
    </div>

    {{-- Repeated --}}
    <div class="input-field col s12 m2">
        <p style="margin-top: 15px; margin-bottom: 15px">
            <label>
                <input id="field_repeated" type="checkbox" data-param="repeated" value="true" />
                <span>{{ uctrans('field.uitype.number.repeated', $module) }}</span>
            </label>
        </p>
        <small class="help primary-text">{{ uctrans('info.uitype.number.repeated', $module) }}</small>
    </div>
</div>
