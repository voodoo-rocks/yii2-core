<?php


namespace vr\core\validators;


use Yii;
use yii\validators\Validator;

/**
 * Class DayOfWeekValidator
 * @package vr\core\validators
 */
class DayOfWeekValidator extends Validator
{
    /**
     *
     */
    const INVALID_DAY_OF_WEEK = 'Invalid day of week';

    /**
     * @var string[]
     */
    public $daysOfWeek = [
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun'
    ];

    /**
     * @param mixed $value
     * @return array|void|null
     */
    protected function validateValue($value)
    {
        if (!in_array($value, $this->daysOfWeek)) {
            return [Yii::t('app', $this->message), []];
        }
    }
}