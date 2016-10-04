<?php

namespace vm\core;
use yii\db\ActiveQuery;

/**
 * Class ActiveQueryRandomTrait
 * @package vm\core
 */
trait ActiveQueryRandomTrait
{
    /**
     * @return $this
     */
    public function random()
    {
        /** @var ActiveQuery $this */
        return $this->orderBy('rand()');
    }
}