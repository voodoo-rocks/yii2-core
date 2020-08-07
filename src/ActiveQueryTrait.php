<?php

namespace vr\core;

use Exception;
use RuntimeException;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Command;
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
     * @return ActiveRecord
     */
    public function first()
    {
        /** @var ActiveQuery $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        return $this->orderBy([
            $primaryKey[0] => SORT_ASC,
        ])->one();
    }

    /**
     * @return ActiveRecord
     */
    public function last()
    {
        /** @var self $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        return $this->orderBy([
            $primaryKey[0] => SORT_DESC,
        ])->one();
    }

    /**
     * @param $condition
     *
     * @return self|ActiveQuery
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
     * @param int $limit
     * @return self
     * @throws Exception
     */
    public function random($limit = 1)
    {
        /** @var self $this */

        if ($limit !== null) {
            $this->limit($limit);
        }

        $expression = ArrayHelper::getValue([
            'mysql' => 'rand()',
            'pgsql' => 'random()'
        ], Yii::$app->db->driverName);

        if (!$expression) {
            throw new RuntimeException(Yii::$app->db->driverName . ' is not supported for this command');
        }

        return $this->orderBy($expression);
    }

    /**
     * @param null $db
     *
     * @return Command
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