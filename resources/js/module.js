import slugify from 'slugify';
import icons from './icons';

export class ModuleTab
{
    constructor() {
        this.tab = $('#module');

        this.bindLabel();
        this.initIconClickListener();
        this.initDefaultViewChangeListener();
        this.initInputChangeListener();
    }

    /**
     * Binds label and icon for the module and modifies card title
     */
    bindLabel() {
        $('#module_label', this.tab).on('keyup', event => {
            // Put module label into the card title
            let label = $(event.currentTarget).val()
            $('.card-title span.label', this.tab).html(label || '&nbsp;');

            // Slugify module label to obtain system name
            $('#module_name', this.tab).val(slugify(label, {lower: true}));
            $('label[for="module_name"]', this.tab).addClass('active');
        });
    }

    /**
     * Dispatches an event when icon button is clicked.
     */
    initIconClickListener() {
        $('a[href="#iconsModal"]', this.tab).on('click', event => {
            // Dispatch a custom event
            let customEvent = new CustomEvent('icons.clicked', {
                detail: {
                    element : event.currentTarget
                }
            });
            dispatchEvent(customEvent);
        });
    }

    /**
     * Display custom route field only if "Other" view is selected.
     */
    initDefaultViewChangeListener() {
        $('#module_default_view', this.tab).on('change', event => {
            if ($(event.currentTarget).val() === 'other') {
                $('#module_custom_route', this.tab).parents('.input-field:first').show();
            } else {
                $('#module_custom_route', this.tab).parents('.input-field:first').hide();
            }
        });
    }

    /**
     * Dispatches an event every time an input field is changed.
     * It will save the new structure.
     */
    initInputChangeListener() {
        $(':input', this.tab).on('change', event => {
            // Wait a little (allows other event listeners to be completed)
            setTimeout(() => {
                let customEvent = new CustomEvent('module.structure.save');
                dispatchEvent(customEvent);
            }, 800);
        });
    }

    /**
     * Returns module structure
     */
    getModuleStructure() {
        let structure = {
            "name": $('#module_name', this.tab).val(),
            "label": $('#module_label', this.tab).val(),
            "icon": $('#module_icon i.material-icons', this.tab).text(),
            // "model": "Uccello\\Crm\\Models\\Account", // TODO: Generate
            "tableName": $('#module_name', this.tab).val(), // TODO: Add field
            "tablePrefix": "",
        }

        if ($('#module_package', this.tab).val()) {
            structure['data'] = {
                "package": $('#module_package', this.tab).val()
            };
        }

        return structure;
    }

    /**
     * Resumes edition.
     * @param {Object} structure
     * @param {String} lang
     */
    resume(structure, lang) {
        // Name
        this.setFieldValue('#module_name', structure.name);

        // Label
        this.setFieldValue('#module_label', structure.label);

        // Icon
        if (structure.icon) {
            $('#module_icon i.material-icons').text(structure.icon);
        }

        // Default view
        let defaultView = structure.data.menu ? structure.data.menu : 'uccello.list';
        this.setFieldValue('#module_default_view', defaultView, false);

        // Visibility
        let visibility = structure.data.private === true ? 'private' : 'public';
        this.setFieldValue('#module_visibility', visibility, false);

        // Package
        this.setFieldValue('#module_package', structure.data.package, false);

        // Administration
        if (structure.data.admin === true) {
            $('#module_admin').prop('checked', true);
        }

        // Change tab title
        if (structure.label) {
            $('span.label', this.tab).text(structure.label);
        }

        // Change tab icon
        if (structure.icon) {
            $('.card-title i.material-icons:first', this.tab).text(structure.icon);
        }
    }

    /**
     * Sets field value and add active css class to label if necessary.
     *
     * @param {String} selector
     * @param {any} value
     * @param {boolean} activateLabel
     */
    setFieldValue(selector, value, activateLabel=true) {
        // Set value
        $(selector, this.tab).val(value).trigger('change');

        // Activate label if exists
        if (activateLabel && value) {
            $(selector, this.tab).parents('.input-field:first').find('label[for]').addClass('active');
        }
    }
}
