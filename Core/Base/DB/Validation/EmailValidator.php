<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base\DB\Validation;


class EmailValidator extends Validator
{
    public $field;
    /**
     * @param \Core\Base\DB\BaseModel $model
     *
     * @return bool
     */
    public function run($model)
    {
        if (!$this->field) {
            return false;
        }
        
        $temp = filter_var($this->field, FILTER_SANITIZE_EMAIL);

        if (filter_var($temp, FILTER_VALIDATE_EMAIL) === false || $temp != $this->field) {
            return false;
        }
        
        return true;
    }
}