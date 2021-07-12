<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;

class CreateModuleDesignerModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
        $this->activateModuleOnDomains($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete module
        Module::where('name', 'module-designer')->forceDelete();
    }

    protected function createModule()
    {
        $module = Module::firstOrCreate([
            'name' => 'module-designer',
            'icon' => 'design_services',
        ],
        [
            'model_class' => null,
            'data' => json_decode('{"package":"uccello\/module-designer","menu":"uccello.index"}')
        ]);

        return $module;
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }
}
