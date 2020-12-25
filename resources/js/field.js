import slugify from 'slugify';

export class FieldModal
{
    constructor() {
        this.modal = $('#fieldModal');

        // this.initCloseButtonClickListener();
        this.bindLabel();
        this.initIconClickListener();
        this.initUitypeChangeListener();
        this.initSaveButtonClickListener();
    }

    /**
     * Reinitialize all fields
     */
    initConfig() {
        $('input[type="text"]', this.modal).val(''); // Text input
        $('input[type="checkbox"]', this.modal).prop('checked', false); // Checkboxes
        $('#field_uitype', this.modal).val('text').formSelect().trigger('change'); // Uitype
        $('#field_displaytype', this.modal).val('everywhere').formSelect(); // Displaytype
        $('#field_icon .material-icons').text(''); // Icon
    }

    /**
     * Closes the modal when the close button is clicked
     */
    // initCloseButtonClickListener() {
    //     $('a.close', this.modal).on('click', event => {
    //         event.preventDefault();
    //         $(this.modal).modal('close');
    //     });
    // }

    /**
     * Binds label and slugify system name
     */
    bindLabel() {
        $(`#field_label`, this.modal).on('keyup', event => {
            // Get label
            let label = $('#field_label', this.modal).val();

            // Slugify module label
            let slug = slugify(label, {lower: true, replacement: '_'});

            // Update system name
            $(`#field_name`, this.modal).val(slug);
            $(`label[for="field_name"]`, this.modal).addClass('active');

            // Update column name
            $(`#field_column`, this.modal).val(slug);
            $(`label[for="field_column"]`, this.modal).addClass('active');
        });
    }

    /**
     * Opens icons modal when user click on field icon
     */
    initIconClickListener() {
        $('a[href="#iconsModal"]', this.modal).on('click', event => {
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
     * Load custom field config when user change uitype
     */
    initUitypeChangeListener() {
        $('#field_uitype', this.modal).on('change', event => {
            let element = $(event.currentTarget);
            let uitype = element.val();

            // Empty config
            $('#custom-config', this.modal).html('');

            // Display custom config title
            $('#custom-config-title', this.modal).show();

            // Display loader
            $('#custom-config-loader', this.modal).show();

            let url = $('meta[name="field-config-url"]').attr('content').replace('UITYPE', uitype);
            $.get(url).then(response => {
                let config = $(response).html();

                // Update html content
                $('#custom-config', this.modal).html(config);

                if (!config) {
                    // Display custom config title
                    $('#custom-config-title', this.modal).hide();
                }

                // Reload JS libraries
                this.reloadJsLibrairies();

                // Hide loader
                $('#custom-config-loader', this.modal).hide();
            });
        });

        // Trigger event to get config for the first uitype
        $('#field_uitype', this.modal).trigger('change');
    }

    initSaveButtonClickListener() {
        $('.save-btn', this.modal).on('click', event => {
            event.preventDefault();

            this.generateFieldConfig();
        });
    }

    generateFieldConfig() {
        let config = {
            blockIndex: $(this.modal).attr('data-block-index'),
            icon: $('#field_icon i.material-icons', this.modal).text(),
            label: $('#field_label', this.modal).val(),
            name: $('#field_name', this.modal).val(),
            uitype: $('#field_uitype', this.modal).val(),
            displaytype: $('#field_displaytype', this.modal).val(),
            data: {
                column: $('#field_column', this.modal).val(),
                required: $('#field_required', this.modal).is(':checked'),
                default: $('#field_default', this.modal).val(),
                large: $('#field_large', this.modal).is(':checked'),
            }
        };

        // Generate custom config
        $('#custom-config :input', this.modal).each((index, el) => {
            let param = $(el).data('param');
            let value = $(el).val();

            // For checkboxes, checks if there are checked
            if ($(el).attr('type') === 'checkbox') {
                value = $(el).is(':checked');
            }

            // Add config only if value is not empty
            if (value !== '') {
                config.data[param] = value;
            }
        });

        // Dispatch custom event with field config
        let customEvent = new CustomEvent('field.config.generated', {
            detail: {
                config: config
            }
        });
        dispatchEvent(customEvent);

        // Empty all fields
        this.initConfig();

        // Close modal
        $(this.modal).modal('close');
    }

    /**
     * Reload JS libraries used by Uccello Core
     */
    reloadJsLibrairies() {
        // Reload materialize
        let event = new CustomEvent('js.init.materialize', {
            detail: {
                element: $('#custom-config', this.modal)
            }
        });
        dispatchEvent(event);

        // Reload librairies used for fields
        event = new CustomEvent('js.init.field.libraries', {
            detail: {
                element: $('#custom-config', this.modal)
            }
        });
        dispatchEvent(event);
    }
}
