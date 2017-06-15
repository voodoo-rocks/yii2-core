<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 15/06/2017
 * Time: 15:30
 */

namespace vr\core;

/**
 * Class Context
 * @package vr\core
 */
class Context extends \yii\base\Model
{
    /**
     * @var
     */
    public $self;

    /**
     *
     */
    public function init()
    {
        $this->self = $this;
        parent::init();
    }

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
}