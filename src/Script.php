<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

use Exception;
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
     * @return bool
     * @throws Exception
     */
    public function execute(): bool
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

            return false;
        }

        return true;
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