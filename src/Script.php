<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

use RuntimeException;
use yii\base\Model;

/**
 * Class Script
 * @package vr\core
 */
class Script extends Model
{
    /**
     * @var bool
     */
    public $throwExceptionOnError = true;

    /**
     * @var
     */
    protected $returnCode;

    /**
     * @return $this
     * @throws ErrorsException
     */
    public function execute()
    {
        try {
            if (!$this->validate()) {
                throw new ErrorsException($this->errors);
            }

            // This method especially returns void
            $this->onExecute();

            if ($this->hasErrors()) {
                throw new ErrorsException($this->errors);
            }
        } catch (ErrorsException $e) {
            if ($this->throwExceptionOnError) {
                throw $e;
            }
        }

        return $this;
    }

    /**
     */
    protected function onExecute()
    {
        throw new RuntimeException(get_called_class() . '::onExecute is not implemented');
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
     * @return array
     */
    public function fields()
    {
        return [];
    }
}