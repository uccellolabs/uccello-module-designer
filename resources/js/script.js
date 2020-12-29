import { ModuleTab } from './module';
import { BlockFieldTab } from './block_field';
import { FilterTab } from './filter';
import { RelationTab } from './relation';
import { IconsModal } from './icons';
import { TranslationTab } from './translation';

class ModuleDesigner
{
    constructor() {
        this.initModuleTab();
        this.initBlockFieldTab();
        this.initFilterTab();
        this.initRelationTab();
        this.initTranslationTab();
        this.initIconsModal();
        this.initMakeStructureEventListener();
        this.initInstallModuleClickListener();
        this.initResumeDesignedModuleClickListener();
        this.showResumeModal();
    }

    /**
     * Inits Module tab.
     */
    initModuleTab() {
        this.moduleTab = new ModuleTab();
    }

    /**
     * Inits Blocks and Fields tab.
     */
    initBlockFieldTab() {
        this.blockFieldTab = new BlockFieldTab();
    }

    /**
     * Inits Filters tab.
     */
    initFilterTab() {
        this.filterTab = new FilterTab();
    }

    /**
     * Inits Relations tab.
     */
    initRelationTab() {
        this.relationTab = new RelationTab();
    }

    initTranslationTab() {
        this.translationTab = new TranslationTab();
    }

    /**
     * Inits Icons modal.
     */
    initIconsModal() {
        new IconsModal();
    }

    /**
     * Adds event listener and make module structure.
     */
    initMakeStructureEventListener() {
        addEventListener('module.structure.save', event => {
            this.saveStructure();
        });
    }

    /**
     * Makes module structure with JSON format and save it.
     */
    saveStructure() {
        // Do not save if it is resuming a creation
        if (this.isResuming === true) {
            return;
        }

        let moduleStructure = this.moduleTab.getModuleStructure();
        let filterStructure = this.filterTab.getFilterStructure();
        let relationStructure = this.relationTab.getRelationStructure();
        let translations = this.translationTab.getTranslations();
        let blockFieldStructure = this.blockFieldTab.getBlocksAndFieldsStructure();

        let structure = Object.assign(moduleStructure, blockFieldStructure, filterStructure, relationStructure, translations);

        if (this.designedModuleId) {
            structure.designed_module_id = this.designedModuleId;
        }

        // console.log(structure);

        // return;

        let url = $('meta[name="save-url"]').attr('content');
        $.post(url, {
            _token: $('meta[name="csrf-token"]').attr('content'),
            structure: JSON.stringify(structure)
        }).then(response => {
            this.designedModuleId = response.id;
        });
    }

    initInstallModuleClickListener() {
        $('#install_module').on('click', event => {
            let url = $('meta[name="install-url"]').attr('content') + '?id=' + this.designedModuleId;

            $.get(url).then(response => {
                // Do nothing for the moment
            });
        });
    }

    initResumeDesignedModuleClickListener() {
        $('#resumeModal a[data-structure]').on('click', event => {
            event.preventDefault();
            let structure = $(event.currentTarget).data('structure');
            let lang = $('html').attr('lang');


        console.log(structure);

            // Resume
            this.isResuming = true; // To stop saving
            this.moduleTab.resume(structure, lang);
            this.blockFieldTab.resume(structure, lang);
            this.filterTab.resume(structure, lang);
            this.relationTab.resume(structure, lang);

            // Close resume modal
            $('#resumeModal').modal('close');

            // Reload JS libraries
            this.reloadJsLibrairies();

            // Wait a little all was been resumed
            setTimeout(() => {
                this.isResuming = false;
            }, 1000);
        });
    }

    /**
     * Displays resume modal if there are pending designed modules.
     */
    showResumeModal() {
        if ($('#resumeModal').data('count') > 0) {
            $('#resumeModal').modal('open');
        }
    }

    /**
     * Reload JS libraries used by Uccello Core
     */
    reloadJsLibrairies() {
        // Reload materialize
        let event = new CustomEvent('js.init.materialize', {
            detail: {
                element: $('.content')
            }
        });
        dispatchEvent(event);

        // Reload librairies used for fields
        event = new CustomEvent('js.init.field.libraries', {
            detail: {
                element: $('.content')
            }
        });
        dispatchEvent(event);
    }
}

new ModuleDesigner();
