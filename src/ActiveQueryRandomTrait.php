<?php

namespace vm\core;

/**
 * Class ActiveQueryRandomTrait
 * @package yii2vm\db
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