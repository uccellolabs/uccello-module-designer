{{-- Fields modal --}}
<div id="fieldModal" class="modal" data-block-index="1">
    <div class="modal-content">
        <h5>
            <i class="material-icons primary-text left">playlist_add</i>{{ uctrans('modal.fields.add_field', $module) }}
            <a href="javascript:void(0)" class="modal-action modal-close btn-flat close right"><i class="material-icons">close</i></a>
        </h5>

        <div class="row" style="margin-top: 30px">
            {{-- Icon --}}
            <div class="input-field col s12 m1">
                <label class="active">{{ uctrans('field.field.icon', $module) }}</label>
                <div style="margin-top: 15px">
                    <a id="field_icon" href="#iconsModal" class="modal-trigger btn-floating grey lighten-3"><i class="material-icons primary-text"></i></a>
                </div>
            </div>

            {{-- Label --}}
            <div class="input-field col s12 m3" data-tooltip="{{ uctrans('info.field.label', $module) }}">
                <input type="text" id="field_label" value="" autocomplete="off">
                <label for="field_label" class="required">{{ uctrans('field.field.label', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.label', $module) }}</small> --}}
            </div>

            {{-- Name --}}
            <div class="input-field col s12 m3" data-tooltip="{{ uctrans('info.field.name', $module) }}">
                <input type="text" id="field_name" value="" autocomplete="off">
                <label for="field_name" class="required">{{ uctrans('field.field.name', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.name', $module) }}</small> --}}
            </div>

            {{-- Column --}}
            <div class="input-field col s12 m3" data-tooltip="{{ uctrans('info.field.column', $module) }}">
                <input type="text" id="field_column" value="" autocomplete="off">
                <label for="field_column" class="required">{{ uctrans('field.field.column', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.column', $module) }}</small> --}}
            </div>

            {{-- Required --}}
            <div class="input-field col s12 m2" data-tooltip="{{ uctrans('info.field.required', $module) }}">
                <p style="margin-top: 15px; margin-bottom: 15px">
                    <label>
                        <input type="checkbox" id="field_required" value="true" />
                        <span>{{ uctrans('field.field.required', $module) }}</span>
                    </label>
                </p>
                {{-- <small class="help primary-text">{{ uctrans('info.field.required', $module) }}</small> --}}
            </div>

            {{-- Uitype --}}
            <div class="input-field col s12 m3" data-tooltip="{{ uctrans('info.field.uitype', $module) }}">
                <select id="field_uitype" data-container="body">
                    @foreach ($uitypes as $uitype)
                        <option value="{{ $uitype->id }}" @if($uitype->name === 'text')selected="selected"@endif>{{ uctrans('uitype.'.$uitype->name, $module) }}</option>
                    @endforeach
                </select>
                <label for="field_uitype" class="required">{{ uctrans('field.field.uitype', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.uitype', $module) }}</small> --}}
            </div>

            {{-- Displaytype --}}
            <div class="input-field col s12 m4" data-tooltip="{{ uctrans('info.field.displaytype', $module) }}">
                <select id="field_displaytype" data-container="body">
                    @foreach ($displaytypes as $displaytype)
                        <option value="{{ $displaytype->id }}">{{ uctrans('displaytype.'.$displaytype->name, $module) }}</option>
                    @endforeach
                </select>
                <label for="field_displaytype" class="required">{{ uctrans('field.field.displaytype', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.displaytype', $module) }}</small> --}}
            </div>

            {{-- Default --}}
            <div class="input-field col s12 m3" data-tooltip="{{ uctrans('info.field.default', $module) }}">
                <input type="text" id="field_default" value="" autocomplete="off">
                <label for="field_default">{{ uctrans('field.field.default', $module) }}</label>
                {{-- <small class="help primary-text">{{ uctrans('info.field.default', $module) }}</small> --}}
            </div>

            {{-- Large --}}
            <div class="input-field col s12 m2" data-tooltip="{{ uctrans('info.field.large', $module) }}">
                <p style="margin-top: 15px; margin-bottom: 15px">
                    <label>
                        <input type="checkbox" id="field_large" value="true" />
                        <span>{{ uctrans('field.field.large', $module) }}</span>
                    </label>
                </p>
                {{-- <small class="help primary-text">{{ uctrans('info.field.large', $module) }}</small> --}}
            </div>

            {{-- TODO: Add rules --}}

            {{-- Custom config title --}}
            <div id="custom-config-title" class="col s12" style="display: none">
                <h6><b>Configuration spécifique</b></h6>
            </div>

            {{-- Loader --}}
            <div id="custom-config-loader" class="center-align" style="display: none">
                <div class="preloader-wrapper big active">
                    <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                    </div>
                </div>
            </div>

            {{-- Custom config --}}
            <div id="custom-config" class="row">
                {{-- Filled in automaticaly by JS --}}
            </div>
        </div>

        {{-- Footer --}}
        <div class="modal-footer">
            <a href="javascript:void(0)" class="btn green save-btn">{{ uctrans('button.save', $module) }}</a>
        </div>
    </div>
</div>
