<?php

namespace vr\core;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveQueryTrait
 * @package vr\core
 */
trait ActiveQueryTrait
{
    /**
     * @var
     */
    private $_forUpdate;

    /**
     * @return self
     */
    public function first()
    {
        /** @var ActiveQuery $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->orderBy([
            $primaryKey[0] => SORT_ASC,
        ])->one();
    }

    /**
     * @return self
     */
    public function last()
    {
        /** @var self $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->orderBy([
            $primaryKey[0] => SORT_DESC,
        ])->one();
    }

    /**
     * @param $condition
     *
     * @return self
     * @throws InvalidConfigException
     */
    public function identifiedBy($condition)
    {
        /** @var ActiveQuery $this */

        if (!ArrayHelper::isAssociative($condition)) {

            $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

            $table = call_user_func([$this->modelClass, 'tableName']);

            if (isset($primaryKey[0])) {
                $condition = [$table . '.' . $primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $this->andWhere($condition);
    }

    /**
     * @param bool $forUpdate
     *
     * @return $this
     */
    public function forUpdate($forUpdate = true)
    {
        $this->_forUpdate = $forUpdate;

        return $this;
    }

    /**
     * @return self
     */
    public function random()
    {
        /** @var self $this */
        return $this->orderBy('rand()');
    }

    /**
     * @param null $db
     *
     * @return \yii\db\Command
     */
    public function createCommand($db = null)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        if ($db === null) {
            $db = $modelClass::getDb();
        }

        if ($this->sql === null) {
            /** @noinspection PhpParamsInspection */
            list($sql, $params) = $db->getQueryBuilder()->build($this);
        } else {
            $sql    = $this->sql;
            $params = $this->params;
        }

        if ($this->_forUpdate) {
            $sql .= ' FOR UPDATE';
        }

        $command = $db->createCommand($sql, $params);
        $this->setCommandCache($command);

        return $command;
    }
}