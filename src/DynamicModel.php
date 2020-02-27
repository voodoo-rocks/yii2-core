<?php


namespace vr\core;


use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * Class DynamicModel
 * @package vr\core
 */
class DynamicModel extends \yii\base\DynamicModel
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $config
     * @return \yii\base\DynamicModel
     * @throws InvalidConfigException
     */
    public static function validateData(array $data, $rules = [], $config = [])
    {
        /* @var $model \yii\base\DynamicModel */
        $model = new static($data, $config);
        if (!empty($rules)) {
            $validators = $model->getValidators();
            foreach ($rules as $rule) {
                if ($rule instanceof Validator) {
                    $validators->append($rule);
                } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                    $validator = Validator::createValidator($rule[1], $model, (array)$rule[0], array_slice($rule, 2));
                    $validators->append($validator);
                } else {
                    throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
                }
            }
        }

        $model->validate();

        return $model;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $attributes = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $attributes,
            $this->scenario        => $attributes,
        ];
    }
}