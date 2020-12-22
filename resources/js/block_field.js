import slugify from 'slugify';
import Swal from 'sweetalert2';

export class BlockFieldTab
{
    constructor() {
        this.tab = $('#block-field');

        this.blocksCount = 0;

        this.initAddBlockClickListener();
        this.bindLabels();

        this.addBlock();
    }

    /**
     * Init click listener for add block button
     */
    initAddBlockClickListener() {
        $('#add-block-btn').on('click', event => {
            this.addBlock();
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
            $(`#block${index}_name`, blockEl).val(slugify(label, {lower: true}));
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

        // Append new block
        $(this.tab).append(blockEl);

        // Increment counter
        this.blocksCount++;
    }
}
