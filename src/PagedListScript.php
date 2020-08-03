<?php

namespace vr\core;

/**
 * Class PagedListScript
 * @package vr\core
 */
class PagedListScript extends Script
{
    /**
     *
     */
    const DEFAULT_LIMIT = 20;

    /**
     * @var int
     */
    public $limit = self::DEFAULT_LIMIT;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var
     */
    public $sort;

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['offset', 'limit'], 'number'],
            ['soft', 'trim']
        ]);
    }
}