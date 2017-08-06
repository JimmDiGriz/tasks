<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base\DB\Validation;


class RequiredValidator extends Validator
{
    public $fields = [];
    
    /**
     * @param \Core\Base\DB\BaseModel $model
     *
     * @return bool
     */
    public function run($model)
    {
        foreach ($this->fields as $field) {
            if (!isset($model->{$field})) {
                return false;
            }
        }
        
        return true;
    }
}