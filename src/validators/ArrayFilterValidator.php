<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/10/2018
 * Time: 08:38
 */

namespace vr\core\validators;

use vr\core\Inflector;
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
    public $allowed = [];

    /**
     * @var bool
     */
    public $variablize = true;

    /**
     * @param \yii\base\Model $model
     * @param string          $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (!empty($this->allowed)) {

            if ($this->variablize) {
                foreach ($this->allowed as $key => $allowed) {
                    $this->allowed[$key] = Inflector::variablize($allowed);
                }
            }

            $model->$attribute = ArrayHelper::filter($model->$attribute, $this->allowed);
        }
    }
}