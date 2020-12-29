import slugify from 'slugify';
import Swal from 'sweetalert2';

export class RelationTab
{
    constructor() {
        this.tab = $('#relation');

        this.relationsCount = 0;

        this.initAddRelationClickListener();
        this.initModuleLabelChange();
        this.bindLabels();

        // this.addRelation();
    }

    /**
     * Init click listener for add relation button
     */
    initAddRelationClickListener() {
        $('#add-relation-btn').on('click', event => {
            event.preventDefault();

            this.addRelation();
        });
    }

    /**
     * Adds module into source and target module list, when current module name changes.
     */
    initModuleLabelChange() {
        $('#module #module_label').on('change', event => {
            this.moduleName = $('#module #module_name').val();
            let moduleLabel = $(event.currentTarget).val();

            $('option.module-name', this.tab).val(this.moduleName).text(moduleLabel);

            // Change relation label
            $('input.relation-label', this.tab).val(moduleLabel).trigger('keyup');
            $('input.relation-label', this.tab).parents('.input-field:first').find('label').addClass('active');

            this.displatchJsLibraryEvent(this.tab);
        });
    }

    /**
     * Init click listener for the deletion of a relationEl
     */
    initDeleteRelationClickListener(relationEl) {
        $('.delete-relation', relationEl).on('click', event => {
            // Display a confirm dialog
            Swal.fire({
                title: relationEl.data('confirm-title'),
                text: relationEl.data('confirm-message'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#017AD5',
                cancelButtonColor: '#F44335',
                confirmButtonText: relationEl.data('confirm-ok'),
                cancelButtonText: relationEl.data('confirm-cancel')
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete relation
                    $(relationEl).remove();
                }
            });
        });
    }

    /**
     * Binds labels for all relations
     */
    bindLabels() {
        $('.card.relation', this.tab).each((index, el) => {
            let relationEl = $(el);
            let relationIndex = relationEl.data('index');

            // Bind relation label
            this.bindRelationLabel(relationEl, relationIndex);
        });
    }

    /**
     * Binds label for each relation and modifies card title
     *
     * @param {Element} relationEl
     * @param {Integer} index
     */
    bindRelationLabel(relationEl, index) {
        $(`#relation${index}_label`, relationEl).on('keyup', event => {
            // Put module label into the card title
            let label = $(event.currentTarget).val()
            $('.card-title span.label', relationEl).html(label || '&nbsp;');

            // Slugify module label to obtain system name
            let slug = slugify(label, {lower: true, replacement: '_'});
            $(`#relation${index}_name`, relationEl).val(slug);
            $(`label[for="relation${index}_name"]`, relationEl).addClass('active');
        });
    }

    /**
     * Dispatches an event every time an input field is changed.
     * It will save the new structure.
     *
     * @param {Element} relationEl
     */
    initInputChangeListener(relationEl) {
        $(':input', relationEl).on('change', event => {
            // Wait a little (allows other event listeners to be completed)
            setTimeout(() => {
                this.dispatchSaveEvent();
            }, 800);
        });
    }

    /**
     * Dispatches an event to inform Uccello to reload JS librairies
     * @param {Element} relationEl
     */
    displatchJsLibraryEvent(relationEl) {
        // Reload materialize
        let event = new CustomEvent('js.init.materialize', {
            detail: {
                element: relationEl
            }
        });
        dispatchEvent(event);

        // Reload librairies used for fields
        event = new CustomEvent('js.init.field.libraries', {
            detail: {
                element: relationEl
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
     * Adds event listener and add update data-block-fields for current module in target module list
     *
     * @param {Element} relationEl
     */
    initFieldChangedEventListener(relationEl) {
        addEventListener('module.field.changed', event => {
            console.log('RELATION');

            this.setTargetModuleFields(relationEl, event.detail.structure);
        });
    }

    /**
     * Automaticaly active or inative target module list depending on source module.
     * If the source module is not the current module, automaticaly select
     * current module from target module and deactivate it
     * @param {Element} relationEl
     */
    initSourceModuleChangeListener(relationEl) {
        $('.source-module', relationEl).on('change', event => {
            let sourceModule = $(event.currentTarget).val();

            // If the source module is not the current module,
            // automaticaly select current module from target module
            // and deactivate it
            if (sourceModule !== this.moduleName) {
                $('.target-module', relationEl).val(this.moduleName).select();
                $('.target-module', relationEl).prop('disabled', true);
            } else {
                $('.target-module', relationEl).prop('disabled', false);
            }

            this.displatchJsLibraryEvent(relationEl);
        });
    }

    /**
     * Automaticaly display target module fields if relation type is 1-n.
     *
     * @param {Element} relationEl
     */
    initTargetModuleChangeListener(relationEl) {
        $('.target-module', relationEl).on('change', event => {
            let targetModule = $(event.currentTarget).val();

            // Delete all blocks and fields
            $('.related-field optgroup', relationEl).remove();

            let relationType = $('.relation-type', relationEl).val();

            if (relationType !== 'n-n') {
                let blocks = JSON.parse($('.target-module option:selected', relationEl).attr('data-block-fields'));

                if (blocks) {
                    for (let block of blocks) {
                        let optgroup = $(`<optgroup label="${block.labelTranslated}"></optgroup>`);

                        for (let field of block.fields) {
                            $(`<option value="${field.name}">${field.label}</option>`).appendTo(optgroup);
                        }

                        $('.related-field', relationEl).append(optgroup);
                    }
                }

                this.displatchJsLibraryEvent(relationEl);
            }
        });
    }

    initRelationTypeChangeListener(relationEl) {
        $('.relation-type', relationEl).on('change', event => {
            let type = $(event.currentTarget).val();

            if (type === 'n-n') {
                $('.related-field', relationEl).val('').prop('disabled', true).parents('.input-field:first').hide();
                $('.relation-method', relationEl).val('getRelatedList');
            } else {
                $('.related-field', relationEl).prop('disabled', false).parents('.input-field:first').show();
                $('.related-field', relationEl).val($('.related-field option:first', relationEl).attr('value'));
                $('.relation-method', relationEl).val('getDependentList');
            }

            this.displatchJsLibraryEvent(relationEl);
        });
    }

    /**
     * Adds a relation and init all event listeners
     */
    addRelation() {
        let index = this.relationsCount + 1;
        let relationEl = $('.relation.template', this.tab).clone();
        relationEl.removeClass('template').attr('data-index', index).show();

        // Change element ids with good id
        $('#relation0_label', relationEl).attr('id', `relation${index}_label`);
        $('label[for="relation0_label"]', relationEl).attr('for', `relation${index}_label`);

        $('#relation0_name', relationEl).attr('id', `relation${index}_name`);
        $('label[for="relation0_name"]', relationEl).attr('for', `relation${index}_name`);

        $('#relation0_type', relationEl).attr('id', `relation${index}_type`).removeClass('browser-default');
        $('label[for="relation0_type"]', relationEl).attr('for', `relation${index}_type`);

        $('#relation0_display_mode', relationEl).attr('id', `relation${index}_display_mode`).removeClass('browser-default');
        $('label[for="relation0_display_mode"]', relationEl).attr('for', `relation${index}_display_mode`);

        $('#relation0_source_module', relationEl).attr('id', `relation${index}_source_module`).removeClass('browser-default');
        $('label[for="relation0_source_module"]', relationEl).attr('for', `relation${index}_source_module`);

        $('#relation0_target_module', relationEl).attr('id', `relation${index}_target_module`).removeClass('browser-default');
        $('label[for="relation0_target_module"]', relationEl).attr('for', `relation${index}_target_module`);

        $('#relation0_related_field', relationEl).attr('id', `relation${index}_related_field`).removeClass('browser-default');
        $('label[for="relation0_related_field"]', relationEl).attr('for', `relation${index}_related_field`);

        $('#relation0_action', relationEl).attr('id', `relation${index}_action`).removeClass('browser-default');
        $('label[for="relation0_action"]', relationEl).attr('for', `relation${index}_action`);

        $('#relation0_method', relationEl).attr('id', `relation${index}_method`);
        $('label[for="relation0_method"]', relationEl).attr('for', `relation${index}_method`);

        // Add event listener for binding
        this.bindRelationLabel(relationEl, index);

        // Add event listener for delete
        this.initDeleteRelationClickListener(relationEl);

        // Add event listener for saving structure
        this.initInputChangeListener(relationEl);

        // Add event listener for adding fields into target module
        this.initFieldChangedEventListener(relationEl);

        // Add event listener for detect when source module change
        this.initSourceModuleChangeListener(relationEl);

        // Add event listener for detect when target module change
        this.initTargetModuleChangeListener(relationEl);

        // Add event listener for detect when relation type change
        this.initRelationTypeChangeListener(relationEl);

        // Append new filter
        $(this.tab).append(relationEl);

        // Reload JS libraries
        this.displatchJsLibraryEvent(relationEl);

        // Increment counter
        this.relationsCount++;

        // Save structure
        this.dispatchSaveEvent();
    }

    /**
     *
     * @param {Element} relationEl
     * @param {Object} structure
     */
    setTargetModuleFields(relationEl, structure) {
        let blockFields = [];
        for (let tab of structure.tabs) {
            for (let block of tab.blocks) {
                let blockField = {
                    label: block.label,
                    labelTranslated: block.labelTranslated,
                    fields: []
                };

                for (let field of block.fields) {
                    if (field.uitype === 'entity') { // Entity
                        blockField.fields.push({
                            name: field.name,
                            label: field.label
                        });
                    }
                }

                if (blockField.fields.length > 0) {
                    blockFields.push(blockField);
                }
            }
        }

        $(`.target-module option.module-name`, relationEl).attr('data-block-fields', JSON.stringify(blockFields));
    }

    /**
     * Returns relation structure
     */
    getRelationStructure() {
        let structure = {
            relatedlists: []
        };

        $('.card.relation:not(.template)', this.tab).each((sequence, el) => {
            let relationEl = $(el);
            let relationIndex = relationEl.attr('data-index');

            let relation = {
                id: null,
                name: $(`#relation${relationIndex}_name`).val(),
                label: $(`#relation${relationIndex}_label`).val(),
                icon: null,
                module: $(`#relation${relationIndex}_source_module`).val(),
                related_module: $(`#relation${relationIndex}_target_module`).val(),
                related_field: $(`#relation${relationIndex}_related_field`).val(),
                type: $(`#relation${relationIndex}_type`).val(),
                method: $(`#relation${relationIndex}_method`).val(),
                sequence: sequence,
                data: {
                    actions: $(`#relation${relationIndex}_action`).val()
                }
            }

            // Add relation
            structure.relatedlists.push(relation);
        });

        return structure;
    }

    /**
     * Resumes edition.
     * @param {Object} structure
     * @param {String} lang
     */
    resume(structure, lang) {
        if (!structure.relatedlists) {
            return;
        }

        this.moduleName = structure.name;

        for (let i=0; i<structure.relatedlists.length; i++) {
            // Add relation if there are more than one relation
            this.addRelation();

            // Get filter information
            let relation = structure.relatedlists[i];
            let relationIndex = i + 1;
            let relationEl = $(`.relation[data-index="${relationIndex}"]`, this.tab);

            // Label
            this.setFieldValue(`#relation${relationIndex}_label`, relation.label);

            // Name
            this.setFieldValue(`#relation${relationIndex}_name`, relation.name);

            // Type
            this.setFieldValue(`#relation${relationIndex}_type`, relation.type, false);

            // Display mode
            let displayMode = relation.tab_id ? 'block' : 'tab';
            this.setFieldValue(`#relation${relationIndex}_display_mode`, displayMode, false);

            // Source module
            this.setFieldValue(`#relation${relationIndex}_source_module`, relation.module, false);

            // Target module
            this.setFieldValue(`#relation${relationIndex}_target_module`, relation.related_module, false);

            // Related field
            this.setFieldValue(`#relation${relationIndex}_related_field`, relation.related_field, false);

            // Action
            let actions = relation.data && relation.data.actions ? relation.data.actions : null;
            this.setFieldValue(`#relation${relationIndex}_action`, actions, false);

            // Method
            this.setFieldValue(`#relation${relationIndex}_method`, relation.method);

            // Change tab title
            if (relation.label) {
                $(`span.label`, relationEl).text(relation.label);
            }

            this.setTargetModuleFields(relationEl, structure);
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
        $(selector).val(value).trigger('change');

        // Activate label if exists
        if (activateLabel && value) {
            $(selector).parents('.input-field:first').find('label[for]').addClass('active');
        }
    }
}
