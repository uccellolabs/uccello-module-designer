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
        let moduleStructure = this.moduleTab.getModuleStructure();
        let blockFieldStructure = this.blockFieldTab.getBlocksAndFieldsStructure();
        let filterStructure = this.filterTab.getFilterStructure();
        let relationStructure = this.relationTab.getRelationStructure();
        let translations = this.translationTab.getTranslations();

        let structure = Object.assign(moduleStructure, blockFieldStructure, filterStructure, relationStructure, translations);

        if (this.designedModuleId) {
            structure.designed_module_id = this.designedModuleId;
        }

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
}

new ModuleDesigner();
