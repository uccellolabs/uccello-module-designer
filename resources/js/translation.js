export class TranslationTab
{
    constructor() {
        this.tab = $('#translation');

        this.initFieldChangedEventListener();

    }

    /**
     * Adds event listener and add update data-block-fields for current module in target module list
     *
     * @param {Element} relationEl
     */
    initFieldChangedEventListener() {
        addEventListener('module.field.changed', event => {
            this.structure = event.detail.structure;
        });
    }

    /**
     * Returns translations TODO: remake
     */
    getTranslations() {
        let translations = {
            fr: {
                block: {},
                field: {},
                relatedlist: {}
            }
        };

        // Module name
        translations.fr[$('#module_name').val()] = $('#module_label').val()

        if (this.structure) {
            for (let block of this.structure.tabs[0].blocks) { // TODO: handle multiple tabs
                translations.fr.block[block.label.replace('block.', '')] = block.labelTranslated;

                for (let field of block.fields) {
                    translations.fr.field[field.name] = field.label;
                }
            }
        }

        return { lang: translations };
    }
}
