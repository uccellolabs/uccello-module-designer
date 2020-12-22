import slugify from 'slugify';
import icons from './icons';

export class ModuleTab
{
    constructor() {
        this.tab = $('#module');

        this.bindLabel();
        this.initIconClickListener();
        this.initDefaultViewChangeListener();
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
}
