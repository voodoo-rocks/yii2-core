<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13/11/2018
 * Time: 23:42
 */

namespace vr\core\validators;

use yii\helpers\ArrayHelper;

/**
 * Trait ArrayFilterTrait
 * @package vr\core\validators
 */
trait ArrayFilterTrait
{
    /**
     * @param $entity
     *
     * @return array|string
     */
    public static function getAllowedAttributes($entity)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $validators = (new self())->getActiveValidators($entity);
        foreach ($validators as $validator) {
            if ($validator instanceof ArrayFilterValidator) {
                return ArrayHelper::getValue($validator, 'allowed');
            }
        }

        return [];
    }
}