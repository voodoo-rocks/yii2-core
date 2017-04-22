<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28/10/2016
 * Time: 11:43
 */

namespace vr\core;

/**
 * Class Model
 * @package vr\api\components
 */
class Model extends \yii\base\Model
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