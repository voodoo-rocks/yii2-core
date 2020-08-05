<?php


namespace vr\core\validators;


/**
 * Class UniqueValidator
 * @package vr\core\validators
 */
class UniqueValidator extends \yii\validators\UniqueValidator
{
    use CustomStatusValidatorTrait;

    /**
     *
     */
    const DEFAULT_STATUS_CODE = 400;
}