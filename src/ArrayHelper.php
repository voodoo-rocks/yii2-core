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

    /**
     * @param $items
     * @param $map
     * @return array
     */
    public static function typecast($items, $map)
    {
        return self::getColumn($items, function (array $item) use ($map) {
            foreach ($map as $attribute => $type) {
                $params = explode(',', $type);
                if (@$params[1]) {
                    $item[$attribute] = sprintf(@$params[1], $item[$attribute]);
                }
                settype($item[$attribute], $params[0]);
            }
            return $item;
        });
    }
}