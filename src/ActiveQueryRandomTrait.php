<?php

namespace vr\core;

use yii\db\ActiveQuery;

/**
 * Class ActiveQueryRandomTrait
 * @package vr\core
 * @deprecated
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