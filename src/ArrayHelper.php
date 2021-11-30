<?php


namespace vr\core;


use Exception;
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
     * @param string|callable $value
     * @return array
     */
    public static function group($associative, string $key = 'key', $value = 'items'): array
    {
        $result = [];

        foreach ($associative as $k => $v) {
            $pair     = !is_string($value) ? call_user_func($value, $v, $k) : [$value => $v];
            $result[] = [
                    $key => $k,
                ] + $pair;
        }

        return $result;
    }

    /**
     * @param array $items
     * @param $map
     * @return array
     */
    public static function typecast(array $items, $map): array
    {
        if (ArrayHelper::isAssociative($items)) {
            return self::typecastAttributes($map, $items);
        }

        return self::getColumn($items, function (array $item) use ($map) {
            return self::typecastAttributes($map, $item);
        });
    }

    /**
     * @param $map
     * @param array $item
     * @return array
     */
    protected static function typecastAttributes($map, array $item): array
    {
        foreach ($map as $attribute => $type) {
            $params = explode(',', $type);
            if (@$params[1]) {
                $item[$attribute] = sprintf(@$params[1], $item[$attribute]);
            }
            settype($item[$attribute], $params[0]);
        }
        return $item;
    }

    /**
     * @param array $items
     * @param string | array $attributes
     * @return int|mixed
     * @throws Exception
     */
    public static function sum(array $items, $attributes)
    {
        $sum = 0;
        foreach ($items as $item) {
            $sum += self::getValue($item, $attributes, 0);
        }

        return $sum;
    }
}