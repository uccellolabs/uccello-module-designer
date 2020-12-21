<?php

namespace Uccello\ModuleDesignerUi\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Uccello\Core\Database\Eloquent\Model;
use Uccello\Core\Support\Traits\UccelloModule;

class ModuleDesginer extends Model
{
    use SoftDeletes;
    use UccelloModule;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'module-designers';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected function initTablePrefix()
    {
        $this->tablePrefix = 'module-designer-ui_';
    }
 
    /**
    * Returns record label
    *
    * @return string
    */
    public function getRecordLabelAttribute() : string
    {
        return $this->id;
    }
}