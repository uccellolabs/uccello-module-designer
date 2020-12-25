import slugify from 'slugify';
import Swal from 'sweetalert2';
import { FieldModal } from './field';

export class BlockFieldTab
{
    constructor() {
        this.tab = $('#block-field');

        this.blocksCount = 0;

        this.initAddBlockClickListener();
        this.initFieldModal();
        this.bindLabels();

        this.addBlock();
    }

    /**
     * Init click listener for add block button
     */
    initAddBlockClickListener() {
        $('#add-block-btn').on('click', event => {
            event.preventDefault();

            this.addBlock();
        });
    }

    /**
     * Init field modal
     */
    initFieldModal() {
        new FieldModal();

        // Add listener for adding field
        addEventListener('field.config.generated', event => {
            let config = event.detail.config;

            let blockEl = $(`.block[data-index="${config.blockIndex}"]`);
            this.addField(blockEl, config);
        });
    }

    /**
     * Init click listener for the deletion of a block
     */
    initDeleteBlockClickListener(blockEl) {
        $('.delete-block', blockEl).on('click', event => {
            // Display a confirm dialog
            Swal.fire({
                title: blockEl.data('confirm-title'),
                text: blockEl.data('confirm-message'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#017AD5',
                cancelButtonColor: '#F44335',
                confirmButtonText: blockEl.data('confirm-ok'),
                cancelButtonText: blockEl.data('confirm-cancel')
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete block
                    $(blockEl).remove();
                }
            });
        });
    }

    /**
     * Change data-block-index param into field modal.
     * It is useful to add the field in the good block.
     *
     * @param {Element} blockEl
     */
    initAddFieldClickListener(blockEl) {
        $('a[href="#fieldModal"]', blockEl).on('click', event => {
            let blockIndex = blockEl.data('index');

            $('#fieldModal').attr('data-block-index', blockIndex);
        });
    }

    /**
     * Binds labels for all blocks
     */
    bindLabels() {
        $('.card.block', this.tab).each((index, el) => {
            let blockEl = $(el);
            let blockIndex = blockEl.data('index');

            // Bind block label
            this.bindBlockLabel(blockEl, blockIndex);
        });
    }

    /**
     * Binds label for each block and modifies card title
     *
     * @param {Element} blockEl
     * @param {Integer} index
     */
    bindBlockLabel(blockEl, index) {
        $(`#block${index}_label`, blockEl).on('keyup', event => {
            // Put module label into the card title
            let label = $(event.currentTarget).val()
            $('.card-title span.label', blockEl).html(label || '&nbsp;');

            // Slugify module label to obtain system name
            let slug = slugify(label, {lower: true, replacement: '_'});
            $(`#block${index}_name`, blockEl).val(slug);
            $(`label[for="block${index}_name"]`, blockEl).addClass('active');
        });
    }

    initIconClickListener(blockEl) {
        $('a[href="#iconsModal"]', blockEl).on('click', event => {
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
     * Dispatches an event every time an input field is changed.
     * It will save the new structure.
     *
     * @param {Element} blockEl
     */
    initInputChangeListener(blockEl) {
        $(':input', blockEl).on('change', event => {
            this.dispatchSaveEvent();
        })
    }

    /**
     * Dispatch custom event to save the complete module stucture.
     */
    dispatchSaveEvent() {
        let customEvent = new CustomEvent('module.structure.save');
        dispatchEvent(customEvent);
    }

    /**
     * Adds a block and init all event listeners
     */
    addBlock() {
        let index = this.blocksCount + 1;
        let blockEl = $('.block.template', this.tab).clone();
        blockEl.removeClass('template').attr('data-index', index).show();

        // Change element ids with good id
        $('#block0_icon', blockEl).attr('id', `block${index}_icon`);
        $('label[for="block0_label"]', blockEl).attr('for', `block${index}_label`);
        $('#block0_label', blockEl).attr('id', `block${index}_label`);
        $('#block0_name', blockEl).attr('id', `block${index}_name`);
        $('label[for="block0_name"]', blockEl).attr('for', `block${index}_name`);

        // Add event listener for binding
        this.bindBlockLabel(blockEl, index);

        // Add event listener for icon click
        this.initIconClickListener(blockEl);

        // Add event listener for delete
        this.initDeleteBlockClickListener(blockEl);

        // Add event listener for adding field
        this.initAddFieldClickListener(blockEl);

        // Add event listener for saving structure
        this.initInputChangeListener(blockEl);

        // Append new block
        $(this.tab).append(blockEl);

        // Increment counter
        this.blocksCount++;

        // Save structure
        this.dispatchSaveEvent();
    }

    /**
     * Adds a field in the related block.
     *
     * @param {Element} blockEl
     * @param {JSON} config
     */
    addField(blockEl, config) {
        let fieldEl = $('.fields-container .template', blockEl).clone();
        fieldEl.removeClass('template').show();

        // Label
        $('.module-field .label', fieldEl).text(config.label);

        // Required
        if (typeof config.data.rules !== 'undefined' && config.data.rules.match('required')) {
            $('.module-field .required', fieldEl).show();
        } else {
            $('.module-field .required', fieldEl).hide();
        }

        // Icon
        if (config.icon) {
            $('.module-field .material-icons', fieldEl).text(config.icon);
        }

        // Large
        if (config.data.large) {
            fieldEl.removeClass('m6');
        }

        // Convert config to JSON and add to data-config attribute
        let jsonConfig = JSON.stringify(config);
        $('.module-field', fieldEl).attr('data-config', jsonConfig);

        // Add field
        $('.fields-container', blockEl).append(fieldEl);

        // Dispatch custom event (useful for adding field into filter columns and conditions)
        let customEvent = new CustomEvent('module.field.changed', {
            detail: {
                structure: this.getBlocksAndFieldsStructure()
            }
        });
        dispatchEvent(customEvent);

        // Save structure
        this.dispatchSaveEvent();
    }

    /**
     * Returns structure
     */
    getBlocksAndFieldsStructure() {
        let structure = {
            tabs: []
        };

        // TODO: Handle multi tabs
        let tab = {
            blocks: []
        };

        // Add all blocks
        $('.card.block:not(.template)', this.tab).each((blockSequence, blockEl) => {
            // Block
            let blockIndex = $(blockEl).attr('data-index');

            let block = {
                label: 'block.' + $(`#block${blockIndex}_name`, blockEl).val(),
                labelTranslated: $(`#block${blockIndex}_label`, blockEl).val(),
                icon: $(`#block${blockIndex}_icon i.material-icons`, blockEl).text(),
                sequence: blockSequence,
                data: null,
                fields: []
            };

            // Add all fields in the block
            $('.module-field:visible', blockEl).each((fieldSequence, fieldEl) => {
                let fieldConfig = $(fieldEl).attr('data-config');

                // TODO: Disapears when a field is adding into filter

                if (fieldConfig) {
                    let field = JSON.parse(fieldConfig);

                    if (field) {
                        field.sequence = fieldSequence;
                        block.fields.push(field);
                    }
                }
            });

            // Add block into the tab
            tab.blocks.push(block);
        });

        // Add tab into the structure
        structure.tabs.push(tab);

        return structure;
    }
}
