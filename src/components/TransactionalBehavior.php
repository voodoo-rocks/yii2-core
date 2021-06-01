<?php


namespace vr\core\components;


use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\db\Exception;
use yii\db\Transaction;

/**
 * Class TransactionalBehavior
 * @package vr\core\components
 */
class TransactionalBehavior extends ActionFilter
{
    /**
     * @var string|null
     */
    public ?string $isolationLevel = null;

    /**
     * @var Transaction|null
     */
    private ?Transaction $_transaction = null;

    /**
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->get('db', false)) {
            $this->_transaction = Yii::$app->db->transaction ?: Yii::$app->db->beginTransaction($this->isolationLevel);
        }
        
        return parent::beforeAction($action);
    }

    /**
     * @param Action $action
     * @param mixed $result
     * @return mixed
     * @throws Exception
     */
    public function afterAction($action, $result)
    {
        if ($this->_transaction) {
            $this->_transaction->commit();
        }

        return parent::afterAction($action, $result);
    }
}