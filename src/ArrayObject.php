<?php
/**
 * @copyright Copyright (c) 2013-2016 Voodoo Mobile Consulting Group LLC
 * @link      https://voodoo.rocks
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace vr\core;

use ArrayAccess;
use Yii;
use yii\base\Arrayable;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ArrayObject
 * @package vr\core
 */
class ArrayObject implements Arrayable, ArrayAccess
{
    /**
     * @var array|object
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        if (!is_array($data)) {
            throw new InvalidParamException('Value cannot be an object or something else. Only arrays accepted');
        }

        ArrayHelper::remove($data, 'optional');

        $this->data = $data;
    }

    /**
     * @deprecated
     *
     * @param $name
     *
     * @return bool
     */
    public function contains($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->data) === 0;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->data;
    }

    /**
     * @param string $name
     *
     * @return mixed|ArrayObject
     */
    public function __get($name)
    {
        $value = ArrayHelper::getValue($this->data, $name, null);

        if (ArrayHelper::isAssociative($value)) {
            return new ArrayObject($value);
        } else if (ArrayHelper::isIndexed($value)) {
            $objects = [];

            foreach ($value as $item) {
                if (is_array($item)) {
                    $objects[] = new ArrayObject($item);
                } else {
                    $objects[] = $item;
                }
            }

            return $objects;
        }

        return $value;
    }

    /**
     * @param       $class
     * @param array $attributes
     *
     * @return object
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     *
     */
    public function createModel($class, $attributes = [])
    {
        /** @var ActiveRecord $model */
        $model = Yii::createObject($class);

        if (!$model || !is_a($model, BaseActiveRecord::className())) {
            throw new Exception('This class is not supported for creating from ArrayObject. Only subclasses of yii\base\Model are supported');
        }

        if ($attributes && !empty($attributes)) {
            foreach ($attributes as $attribute) {
                $model->setAttribute($attribute, $this->{$attribute});
            }
        } else {
            $model->setAttributes($this->getValues(), true);
        }

        return $model;
    }

    /**
     * Returns the list of fields that should be returned by default by [[toArray()]] when no specific fields are
     * specified. A field is a named element in the returned array by [[toArray()]]. This method should return an array
     * of field names or field definitions. If the former, the field name will be treated as an object property name
     * whose value will be used as the field value. If the latter, the array key should be the field name while the
     * array value should be the corresponding field definition which can be either an object property name or a PHP
     * callable returning the corresponding field value. The signature of the callable should be:
     * ```php
     * function ($field, $model) {
     *     // return field value
     * }
     * ```
     * For example, the following code declares four fields:
     * - `email`: the field name is the same as the property name `email`;
     * - `firstName` and `lastName`: the field names are `firstName` and `lastName`, and their
     *   values are obtained from the `first_name` and `last_name` properties;
     * - `fullName`: the field name is `fullName`. Its value is obtained by concatenating `first_name`
     *   and `last_name`.
     * ```php
     * return [
     *     'email',
     *     'firstName' => 'first_name',
     *     'lastName' => 'last_name',
     *     'fullName' => function ($model) {
     *         return $model->first_name . ' ' . $model->last_name;
     *     },
     * ];
     * ```
     * @return array the list of field names or field definitions.
     * @see toArray()
     */
    public function fields()
    {
        return array_keys($this->data);
    }

    /**
     * Returns the list of additional fields that can be returned by [[toArray()]] in addition to those listed in
     * [[fields()]]. This method is similar to [[fields()]] except that the list of fields declared by this method are
     * not returned by default by [[toArray()]]. Only when a field in the list is explicitly requested, will it be
     * included in the result of [[toArray()]].
     * @return array the list of expandable field names or field definitions. Please refer
     * to [[fields()]] on the format of the return value.
     * @see toArray()
     * @see fields()
     */
    public function extraFields()
    {
    }

    /**
     * Converts the object into an array.
     *
     * @param array   $fields    the fields that the output array should contain. Fields not specified
     *                           in [[fields()]] will be ignored. If this parameter is empty, all fields as specified
     *                           in
     *                           [[fields()]] will be returned.
     * @param array   $expand    the additional fields that the output array should contain.
     *                           Fields not specified in [[extraFields()]] will be ignored. If this parameter is empty,
     *                           no extra fields will be returned.
     * @param boolean $recursive whether to recursively return array representation of embedded objects.
     *
     * @return array the array representation of the object
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $array = [];

        foreach ($this->data as $key => $value) {
            if ($recursive && is_array($value)) {
                $array[$key] = (new ArrayObject($value))->toArray([], [], $recursive);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return ArrayHelper::isIndexed($this->data) && count($this->data) > $offset;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
    }
}