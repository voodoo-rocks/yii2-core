<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

use yii\base\UserException;

/**
 * Class Script
 * @package vr\core
 */
class Script extends \yii\base\Model
{
    /**
     * @var bool
     */
    public $isExecuted;
    /**
     * @var bool
     */
    public $oneTimeExecution = true;

    /**
     * @param array $config
     *
     * @return array
     * @throws UserException
     */
    public static function run(array $config)
    {
        $script = \Yii::createObject(get_called_class(), $config);

        $script->execute();

        return $script->errors;
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws InvalidCallException
     * @throws InvalidArgumentException
     * @throws UserException
     */
    public function execute(): bool
    {
        if ($this->isExecuted && $this->oneTimeExecution) {
            throw new InvalidCallException('This script cannot be executed more than once');
        }

        if (!$this->validate()) {
            return false;
        }

        // This method especially returns void
        $this->onExecute();
        $this->isExecuted = true;

        if ($this->hasErrors()) {
            return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     * @throws UserException
     */
    protected function onExecute()
    {
        throw new \RuntimeException(get_called_class() . '::onExecute is not implemented');
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => IgnoreAttributesBehaviour::className(),
            ],
        ];
    }

    /**
     * @return bool
     */
    public function getIsExecuted(): bool
    {
        return $this->isExecuted;
    }
}