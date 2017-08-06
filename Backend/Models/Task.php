<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Site\Models;

use Core\Base\DB\BaseModel;
use Core\Base\DB\Validation\EmailValidator;
use Core\Base\DB\Validation\RequiredValidator;
use Core\Base\DB\Validation\StringValidator;

/**
 * @property int $id
 * @property string $user_name
 * @property string $user_email
 * @property string $text
 * @property string $photo
 * @property bool $is_completed
 */
class Task extends BaseModel
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'tasks';
    }

    public function getColumnMap(): array 
    {
        return [
            'id',
            'user_name',
            'user_email',
            'text',
            'photo',
            'is_completed',
        ];
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return 'id';
    }
    
    public function rules(): array
    {
        return [
            [
                'class' => RequiredValidator::class,
                'options' => [
                    'fields' => [
                        'user_name',
                        'user_email',
                        'text',
                    ],
                ],
            ],
            [
                'class' => StringValidator::class,
                'options' => [
                    'field' => 'user_name',
                    'minLength' => 3,
                    'maxLength' => 100,
                ],
            ],
            [
                'class' => StringValidator::class,
                'options' => [
                    'field' => 'user_email',
                    'minLength' => 10,
                    'maxLength' => 100,
                ],
            ],
            [
                'class' => StringValidator::class,
                'options' => [
                    'field' => 'text',
                    'minLength' => 20,
                    'maxLength' => 1000,
                ],
            ],
            [
                'class' => EmailValidator::class,
                'options' => [
                    'field' => 'user_email',
                ],
            ]
        ];
    }
}