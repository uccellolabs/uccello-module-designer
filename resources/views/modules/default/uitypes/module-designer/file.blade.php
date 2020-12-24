<div>
    {{-- Path --}}
    <div class="input-field col s12 m6">
        <input id="field_path" data-param="path">
        <label for="field_path" class="required">{{ uctrans('field.uitype.file.path', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.file.path', $module) }}</small>
    </div>

    {{-- Multipe --}}
    <div class="input-field col s12 m6">
        <p style="margin-top: 15px; margin-bottom: 15px">
            <label>
                <input id="field_multiple" type="checkbox" data-param="multiple" value="true" />
                <span>{{ uctrans('field.uitype.file.public', $module) }}</span>
            </label>
        </p>
        <small class="help primary-text">{{ uctrans('info.uitype.file.public', $module) }}</small>
    </div>
</div>
