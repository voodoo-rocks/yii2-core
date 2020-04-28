<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 04/03/2017
 * Time: 21:18
 */

namespace vr\core\validators;

use vr\core\DynamicModel;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
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
    public $objectify = false;

    /**
     * @var bool
     * @deprecated
     */
    public $allowArrays = false;

    /**
     * @var bool
     */
    public $allowArray = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
    }

    /**
     * @param Model $model
     * @param string $attribute
     *
     * @throws InvalidConfigException
     */
    public function validateAttribute($model, $attribute)
    {
        $toValidate = $model->$attribute;

        if (!is_array($toValidate)) {
            $this->addError($model, $attribute, $this->message, []);
            return;
        }

        if (($this->allowArray || $this->allowArrays) && ArrayHelper::isIndexed($toValidate)) {
            foreach ($toValidate as $item) {
                $this->validateItem($model, $attribute, $item);
            }
        } else {
            $this->validateItem($model, $attribute, $toValidate);
        }
    }

    /**
     * @param $model
     * @param $attribute
     * @param array $attributes
     * @throws InvalidConfigException
     */
    protected function validateItem(Model $model, $attribute, array $attributes): void
    {
        // Add attributes missing in the model but mentioned in rules
        foreach ($this->rules as $rule) {
            $ruleAttributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];

            $on = ArrayHelper::getValue($rule, 'on', []);
            if ($on && is_string($on)) {
                $on = [$on];
            }

            if ($on && !in_array($model->scenario, $on)) {
                ArrayHelper::removeValue($this->rules, $rule);
                continue;
            }

            $except = ArrayHelper::getValue($rule, 'except', []);
            if ($except && is_string($except)) {
                $except = [$except];
            }

            if ($except && in_array($model->scenario, $except)) {
                ArrayHelper::removeValue($this->rules, $rule);
                continue;
            }

            foreach ($ruleAttributes as $ruleAttribute) {
                $attributes = $attributes + [
                        $ruleAttribute => null,
                    ];
            }
        }

        $dynamic = DynamicModel::validateData($attributes, $this->rules, [
            'scenario' => $model->scenario
        ]);

        $model->addErrors($dynamic->errors);

        if ($this->objectify) {
            $model->$attribute = (object)$model->$attribute;
        }
    }
}