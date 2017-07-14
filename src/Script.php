<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 14/07/2017
 * Time: 12:15
 */

namespace vr\core;

/**
 * Class Script
 * @package vr\core
 */
abstract class Script extends \yii\base\Model
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => IgnoreAttributesBehaviour::className(),
            ],
        ];
    }

    /**
     * @return bool
     */
    abstract public function execute(): bool;
}