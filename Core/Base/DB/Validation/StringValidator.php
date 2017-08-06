<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base\DB\Validation;


class StringValidator extends Validator
{
    public $minLength = 0;
    public $maxLength = 0;
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
        
        $length = mb_strlen($model->{$this->field});
        
        if ($length < $this->minLength || $length > $this->maxLength) {
            return false;
        }
        
        return true;
    }
}