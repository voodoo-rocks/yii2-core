<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

use yii\base\InvalidCallException;

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

    public $throwExceptionOnError = false;

    /**
     * @return bool
     * @throws \Exception
     */
    public function execute(): bool
    {
        if ($this->isExecuted && $this->oneTimeExecution) {
            throw new InvalidCallException('This script cannot be executed more than once');
        }

        if (!$this->validate()) {
            return false;
        }

        try {
            // This method especially returns void
            $this->onExecute();

            $this->isExecuted = true;
        } catch (\Exception $e) {
            if ($this->throwExceptionOnError) {
                throw $e;
            }
        }

        return !$this->hasErrors();
    }

    /**
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
                'class' => IgnoreAttributesBehaviour::class,
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