<div>
    {{-- Admin --}}
    <div class="input-field col s12 m6">
        <p style="margin-top: 15px; margin-bottom: 15px">
            <label>
                <input id="field_admin" type="checkbox" data-param="admin" value="true" checked />
                <span>{{ uctrans('field.uitype.module_list.admin', $module) }}</span>
            </label>
        </p>
        <small class="help primary-text">{{ uctrans('info.uitype.module_list.admin', $module) }}</small>
    </div>
</div>
