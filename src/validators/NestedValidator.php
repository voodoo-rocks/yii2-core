<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 04/03/2017
 * Time: 21:18
 */

namespace vr\core\validators;

use yii\base\DynamicModel;
use yii\validators\Validator;

/**
 * Class NestedValidator
 * @package app\modules\api\validators
 */
class NestedValidator extends Validator
{
    /**
     * @var
     */
    public $rules;

    /**
     * @var bool
     */
    public $objectize = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = \Yii::t('yii', '{attribute} is invalid.');
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string          $attribute
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function validateAttribute($model, $attribute)
    {
        $attributes = $model->$attribute;

        if (!is_array($attributes)) {
            $this->addError($model, $attribute, $this->message, []);

            return;
        }

        // Add attributes missing in the model but mentioned in rules
        foreach ($this->rules as $rule) {
            $ruleAttributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];

            foreach ($ruleAttributes as $ruleAttribute) {
                $attributes = $attributes + [
                        $ruleAttribute => null,
                    ];
            }
        }

        $dynamic = DynamicModel::validateData($attributes, $this->rules);
        $model->addErrors($dynamic->errors);

        if ($this->objectize) {
            $model->$attribute = (object)$model->$attribute;
        }
    }
}