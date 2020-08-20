<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 09/11/2016
 * Time: 15:34
 */

namespace vr\core;

use yii\base\Behavior;

/**
 * Class IgnoreAttributesBehaviour
 * @package vr\api\components
 * @deprecated
 */
class IgnoreAttributesBehaviour extends Behavior
{
    /**
     * @param string $name
     * @param bool   $checkVars
     *
     * @return bool
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return true;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
    }
}