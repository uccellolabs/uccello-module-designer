import { ModuleTab } from './module';
import { BlockFieldTab } from './block_field';
import { FilterTab } from './filter';
import { IconsModal } from './icons';

class ModuleDesigner
{
    constructor() {
        this.initModuleTab();
        this.initBlockFieldTab();
        this.initFilterTab();
        this.initIconsModal();
        this.initMakeStructureEventListener();
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

        let structure = Object.assign(moduleStructure, blockFieldStructure, filterStructure);

        console.log(structure);
    }
}

new ModuleDesigner();
