<?php

namespace vr\core;

/**
 * Class PagedListScript
 * @package vr\core
 * @property array $orderBy
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
            ['sort', 'trim']
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getOrderBy()
    {
        if (!$this->sort) {
            return [];
        }

        $params    = explode('+', $this->sort);
        $attribute = Inflector::variablize(ArrayHelper::getValue($params, 0));

        if ($this->orderable() && !in_array($attribute, $this->orderable())) {
            return [];
        }

        $direction = ArrayHelper::getValue([
            'asc'  => SORT_ASC,
            'desc' => SORT_DESC,
        ], ArrayHelper::getValue($params, 1, 'asc'));

        return [
            $attribute => $direction
        ];
    }

    /**
     * @return array
     */
    protected function orderable()
    {
        return [];
    }
}