import { ModuleTab } from './module';
import { BlockFieldTab } from './block_field';
import { IconsModal } from './icons';

class ModuleDesigner
{
    constructor() {
        this.initModuleTab();
        this.initBlockFieldTab();
        this.initIconsModal();
    }

    // Module
    initModuleTab() {
        new ModuleTab();
    }

    // Blocks and Fields
    initBlockFieldTab() {
        new BlockFieldTab();
    }

    // Icons modal
    initIconsModal() {
        new IconsModal();
    }
}

new ModuleDesigner();
