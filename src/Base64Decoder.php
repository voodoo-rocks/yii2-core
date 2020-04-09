<?php


namespace vr\core;


use yii\base\Model;
use yii\validators\Validator;

/**
 * Class Base64Decoder
 * @package vr\core
 */
class Base64Decoder extends Validator
{
    const DEFAULT_BASE64_HEADER = 'data:text/csv;base64,';

    public $header = self::DEFAULT_BASE64_HEADER;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if (substr_compare($model->$attribute, $this->header, 0, strlen($this->header)) === 0) {
            $model->$attribute = substr($model->$attribute, strlen($this->header));
        }

        $model->$attribute = base64_decode($model->$attribute);

        if (empty($model->$attribute)) {
            $model->addError($attribute, 'Invalid data format');
        }
    }
}