<div>
    {{-- Choices --}}
    <div class="input-field col s12 m6">
        <textarea id="field_choices" data-param="choices"></textarea>
        <label for="field_choices" class="required">{{ uctrans('field.uitype.choice.choices', $module) }}</label>
        <small class="help primary-text">{{ uctrans('info.uitype.choice.choices', $module) }}</small>
    </div>

    {{-- Multipe --}}
    <div class="input-field col s12 m6">
        <p style="margin-top: 15px; margin-bottom: 15px">
            <label>
                <input id="field_multiple" type="checkbox" data-param="multiple" value="true" />
                <span>{{ uctrans('field.uitype.choice.multiple', $module) }}</span>
            </label>
        </p>
        <small class="help primary-text">{{ uctrans('info.uitype.choice.multiple', $module) }}</small>
    </div>
</div>
