import { drop } from 'lodash';
import slugify from 'slugify';
import Swal from 'sweetalert2';

export class FilterTab
{
    constructor() {
        this.tab = $('#filter');

        this.filtersCount = 0;
        this.fieldsByBlocks = {};

        this.initAddFilterClickListener();
        this.bindLabels();

        this.addFilter();
    }

    /**
     * Init click listener for add filter button
     */
    initAddFilterClickListener() {
        $('#add-filter-btn').on('click', event => {
            event.preventDefault();

            this.addFilter();
        });
    }

    /**
     * Init click listener for the deletion of a filter
     */
    initDeleteFilterClickListener(filterEl) {
        $('.delete-filter', filterEl).on('click', event => {
            // Display a confirm dialog
            Swal.fire({
                title: filterEl.data('confirm-title'),
                text: filterEl.data('confirm-message'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#017AD5',
                cancelButtonColor: '#F44335',
                confirmButtonText: filterEl.data('confirm-ok'),
                cancelButtonText: filterEl.data('confirm-cancel')
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete filter
                    $(filterEl).remove();
                }
            });
        });
    }

    /**
     * Binds labels for all filters
     */
    bindLabels() {
        $('.card.filter', this.tab).each((index, el) => {
            let filterEl = $(el);
            let filterIndex = filterEl.data('index');

            // Bind filter label
            this.bindFilterLabel(filterEl, filterIndex);
        });
    }

    /**
     * Binds label for each filter and modifies card title
     *
     * @param {Element} filterEl
     * @param {Integer} index
     */
    bindFilterLabel(filterEl, index) {
        $(`#filter${index}_label`, filterEl).on('keyup', event => {
            // Put module label into the card title
            let label = $(event.currentTarget).val()
            $('.card-title span.label', filterEl).html(label || '&nbsp;');

            // Slugify module label to obtain system name
            let slug = slugify(label, {lower: true, replacement: '_'});
            $(`#filter${index}_name`, filterEl).val(slug);
            $(`label[for="filter${index}_name"]`, filterEl).addClass('active');
        });
    }

    /**
     * Dispatches an event every time an input field is changed.
     * It will save the new structure.
     *
     * @param {Element} filterEl
     */
    initInputChangeListener(filterEl) {
        $(':input', filterEl).on('change', event => {
            this.dispatchSaveEvent();
        })
    }

    /**
     * Dispatches an event to inform Uccello to reload JS librairies
     * @param {Element} filterEl
     */
    displatchJsLibraryEvent(filterEl) {
        // Reload materialize
        let event = new CustomEvent('js.init.materialize', {
            detail: {
                element: filterEl
            }
        });
        dispatchEvent(event);

        // Reload librairies used for fields
        event = new CustomEvent('js.init.field.libraries', {
            detail: {
                element: filterEl
            }
        });
        dispatchEvent(event);
    }

    /**
     * Dispatch custom event to save the complete module stucture.
     */
    dispatchSaveEvent() {
        let customEvent = new CustomEvent('module.structure.save');
        dispatchEvent(customEvent);
    }

    /**
     * Adds event listener and add new field in dropdown lists.
     *
     * @param {Element} filterEl
     */
    initFieldChangedEventListener(filterEl) {
        addEventListener('module.field.changed', event => {
            this.structure = event.detail.structure;

            // For each filter, update dropdown and condition fields
            $('.card.filter:not(.template)', this.tab).each((index, el) => {
                let filterEl = $(el);

                // Add fields
                this.addFields(filterEl);
            });
        });
    }

    /**
     * Inits add condition button click listener.
     *
     * @param {Element} filterEl
     */
    initAddConditionClickListener(filterEl) {
        $('.add-condition', filterEl).on('click', event => {
            this.addCondition(filterEl);
        });
    }

    /**
     * Adds a filter and init all event listeners
     */
    addFilter() {
        let index = this.filtersCount + 1;
        let filterEl = $('.filter.template', this.tab).clone();
        filterEl.removeClass('template').attr('data-index', index).show();

        // Change element ids with good id
        $('#filter0_icon', filterEl).attr('id', `filter${index}_icon`);
        $('label[for="filter0_label"]', filterEl).attr('for', `filter${index}_label`);
        $('#filter0_label', filterEl).attr('id', `filter${index}_label`);
        $('#filter0_name', filterEl).attr('id', `filter${index}_name`);
        $('label[for="filter0_name"]', filterEl).attr('for', `filter${index}_name`);
        $('a[data-target="filter0_dropdown"]', filterEl).attr('data-target', `filter${index}_dropdown`);
        $('#filter0_dropdown', filterEl).attr('id', `filter${index}_dropdown`);
        $('#filter0_condition0_field', filterEl).attr('id', `filter${index}_condition0_field`);
        $('#filter0_condition0_value', filterEl).attr('id', `filter${index}_condition0_value`);

        // Add event listener for binding
        this.bindFilterLabel(filterEl, index);

        // Add event listener for delete
        this.initDeleteFilterClickListener(filterEl);

        // Add event listener for saving structure
        this.initInputChangeListener(filterEl);

        // Add event listener for adding fields into filter
        this.initFieldChangedEventListener(filterEl);

        // Add event listner for adding conditions into filter
        this.initAddConditionClickListener(filterEl);

        // Add fields
        this.addFields(filterEl);

        // Append new filter
        $(this.tab).append(filterEl);

        // Reload JS libraries
        this.displatchJsLibraryEvent(filterEl);

        // Increment counter
        this.filtersCount++;

        // Add first condition
        // this.addCondition(filterEl);

        // Save structure
        this.dispatchSaveEvent();
    }

    /**
     * Adds all fields in columns list and conditions.
     *
     * @param {Element} filterEl
     */
    addFields(filterEl) {
        if (!this.structure) {
            return;
        }

        let filterIndex = filterEl.attr('data-index');

        let dropdownEl = $(`#filter${filterIndex}_dropdown`, filterEl);
        let conditionFieldEl = $(`.condition-field:first`, filterEl);

        // Remove all fields
        $('li:not(.template)', dropdownEl).remove();
        $('optgroup', conditionFieldEl).remove();

        // Add fields
        for (let i=0; i<this.structure.tabs[0].blocks.length; i++) { // TODO: Handle multi tabs
            let block = this.structure.tabs[0].blocks[i];

            // Add a divider to divide each block
            if (i > 0) {
                $('<li class="divider" tabindex="-1"></li>').appendTo(dropdownEl);
            }

            // Add optgroup with block Label
            $(`<li class="optgroup" tabindex="-1"><span>${block.labelTranslated}</span></li>`).appendTo(dropdownEl);

            // Add an optgroup by block
            let optgroupEl = $(`<optgroup label="${block.labelTranslated}"></optgroup>`);

            // Add all fields
            for (let field of block.fields) {
                if (field.displaytype === 'hidden') { // TODO: Checks instead field.isListable (e.g. with attribute data-listable)
                    continue;
                }

                // Add field into dropdown
                let liEl = $('li.template', dropdownEl).clone().removeClass('template').show();
                $('a', liEl).attr('data-name', field.name).text(field.label);

                // Add click lister to add column in displayed columns lists
                liEl.on('click', liEvent => {
                    liEvent.preventDefault();

                    // Don't do noting if the field was already added
                    if ($(`.chip[data-name="${field.name}"]`).length > 0) {
                        return;
                    }

                    // Add chip
                    let chipEl = $('.chip.template', filterEl).clone().removeClass('template').show();
                    chipEl.attr('data-name', field.name);
                    $('.label', chipEl).text(field.label);

                    // Disabled li from field list
                    // liEl.prop('disabled', true);

                    // Save structure
                    this.dispatchSaveEvent();

                    // Add delete listener
                    $('a.delete', chipEl).on('click', closeEvent => {
                        closeEvent.preventDefault();

                        // Remove chip
                        chipEl.remove();

                        // Enabled li from field list
                        // liEl.prop('disabled', false);

                        // Save structure
                        this.dispatchSaveEvent();
                    })

                    $('.displayed-columns', filterEl).append(chipEl);

                    // Save structure
                    this.dispatchSaveEvent();
                })
                dropdownEl.append(liEl);

                // Add field into condition fields
                //$(`<option value="${field.name}">${field.label}</option>`).appendTo(optgroupEl);
                // let optionEl = $('option.template', optgroupel).clone().removeClass('template').show();
                // optionEl.prop('value', field.name).text(field.label);
            }

            conditionFieldEl.append(optgroupEl);
        }

        // Reload JS libraries
        this.displatchJsLibraryEvent(filterEl);
    }

    addCondition(filterEl) {
        let conditionEl = $('.condition.template', filterEl).clone();
        conditionEl.removeClass('template').show();

        // Replace ids and attributes
        // $('#filter0')

        $('.conditions', filterEl).append(conditionEl);
    }

    /**
     * Returns filter structure
     */
    getFilterStructure() {
        let structure = {
            filters: []
        };

        $('.card.filter:not(.template)', this.tab).each((sequence, el) => {
            let filterEl = $(el);
            let filterIndex = filterEl.attr('data-index');

            let filter = {
                id: null,
                name: $(`#filter${filterIndex}_name`).val(),
                label: $(`#filter${filterIndex}_label`).val(),
                columns: [],
                conditions: [] // TODO: Generate
            }

            // Add displayed columns
            $('.displayed-columns .chip:not(.template)').each((index, chipEl) => {
                filter.columns.push($(chipEl).attr('data-name'));
            })

            // Add filter
            structure.filters.push(filter);
        });

        return structure;
    }
}
