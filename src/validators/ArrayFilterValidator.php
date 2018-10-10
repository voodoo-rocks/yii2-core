<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/10/2018
 * Time: 08:38
 */

namespace vr\core\validators;

use yii\helpers\ArrayHelper;
use yii\validators\Validator;

/**
 * Class ArrayFilterValidator
 * @package vr\core\validators
 */
class ArrayFilterValidator extends Validator
{
    /**
     * @var
     */
    public $allowed;

    /**
     * @param \yii\base\Model $model
     * @param string          $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (!empty($this->allowed)) {
            $model->$attribute = ArrayHelper::filter($model->$attribute, $this->allowed);
        }
    }
}