{{-- Icons modal --}}
<div id="iconsModal" class="modal">
    <div>
        <h5>
            <i class="material-icons primary-text left">check_circle</i>{{ uctrans('modal.icons.select_icon', $module) }}
            <a href="javascript:void(0)" class="modal-action modal-close btn-flat close right"><i class="material-icons">close</i></a>
        </h5>

        <div id="icons-searchbar">
            <div class="input-field">
                {{-- <i class="material-icons prefix">search</i> --}}
                <input type="text" placeholder="{{ uctrans('modal.icons.search_icon', $module) }}">
            </div>
        </div>

        <div id="icons-container" class="row">
            <div class="template icon-col col s6 m4">
                <div class="icon center-align">
                    <i class="material-icons">extension</i><br>
                    <span class="label">extension</span>
                </div>
            </div>
            {{-- Filled automaticaly by JS --}}
        </div>
    </div>
</div>
