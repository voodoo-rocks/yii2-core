<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

/**
 * Class Script
 * @package vr\core
 */
class Script extends \yii\base\Model
{
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
     * @var bool
     */
    public $isExecuted;

    /**
     * @var bool
     */
    public $oneTimeExecution = true;

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
        throw new UserException('Not implemented. Please implement in inherited classes');
    }

    /**
     * @return bool
     */
    public function getIsExecuted(): bool
    {
        return $this->isExecuted;
    }
}