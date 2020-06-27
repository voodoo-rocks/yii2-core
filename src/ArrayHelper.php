<?php


namespace vr\core;


use yii\helpers\BaseArrayHelper;

/**
 * Class ArrayHelper
 * @package vr\core
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * @param $associative
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function group($associative, $key = 'key', $value = 'items')
    {
        $result = [];

        foreach ($associative as $k => $v) {
            $result[] = [
                $key   => $k,
                $value => $v,
            ];
        }

        return $result;
    }
}