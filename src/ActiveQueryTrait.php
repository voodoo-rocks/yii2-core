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
     * @return $this
     */
    public function first()
    {
        /** @var ActiveQuery $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        return $this->orderBy([
            $primaryKey[0] => SORT_ASC
        ])->one();
    }

    /**
     * @return ActiveRecord
     */
    public function last()
    {
        /** @var ActiveQuery $this */

        $primaryKey = call_user_func([$this->modelClass, 'primaryKey']);

        return $this->orderBy([
            $primaryKey[0] => SORT_DESC
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
     * @return $this
     */
    public function random()
    {
        /** @var ActiveQuery $this */
        return $this->orderBy('rand()');
    }
}