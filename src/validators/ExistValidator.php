<?php


namespace vr\core\validators;


/**
 * Class ExistValidator
 * @package vr\core\validators
 */
class ExistValidator extends \yii\validators\ExistValidator
{
    use CustomStatusValidatorTrait;
}