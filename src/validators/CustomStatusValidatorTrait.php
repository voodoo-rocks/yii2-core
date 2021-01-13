<?php


namespace vr\core\validators;


use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Trait CustomStatusValidatorTrait
 * @package vr\core\validators
 */
trait CustomStatusValidatorTrait
{
    /**
     * @var int
     */
    public $statusCode = 400;

    /**
     * @param Model $model
     * @param string $attribute
     * @throws HttpException
     */
    public function validateAttribute($model, $attribute)
    {
        $errors = $model->getErrors($attribute);
        parent::validateAttribute($model, $attribute);

        if ($model->hasErrors($attribute) && $errors !== $model->getErrors($attribute)) {
            throw new HttpException($this->statusCode, Yii::t('app', $this->message, [
                'attribute' => $attribute,
                'value'     => $model->$attribute
            ]));
        }
    }

    /**
     * @param mixed $value
     * @param null $error
     * @return bool|void
     * @throws HttpException
     */
    public function validate($value, &$error = null)
    {
        if (!parent::validate($value, $error)) {
            throw new HttpException($this->statusCode, $error);
        }
    }
}