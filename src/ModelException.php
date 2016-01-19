<?php
/**
 * @copyright Copyright (c) 2013-2016 Voodoo Mobile Consulting Group LLC
 * @link      https://voodoo.rocks
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace vm\core;

use yii\base\Exception;
use yii\base\Model;

/**
 * Class ModelException
 * @package yii2vm\base
 */
class ModelException extends Exception
{
    /**
     * @var null|Model
     */
    public $entity = null;

    /**
     * @param Model $entity
     */
    public function __construct(Model $entity)
    {
        $messages     = [];
        $this->entity = $entity;

        foreach ($entity->errors as $attribute => $errors) {
            $messages[] = implode(' : ', [
                'attribute' => $attribute,
                'message'   => implode(', ', (array)$errors),
            ]);
        }

        parent::__construct(implode(PHP_EOL, $messages));
    }
}