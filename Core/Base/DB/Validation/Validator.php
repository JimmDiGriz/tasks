<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base\DB\Validation;

abstract class Validator
{
    /**
     * @param \Core\Base\DB\BaseModel $model
     * 
     * @return bool
     */
    public abstract function run($model);
}