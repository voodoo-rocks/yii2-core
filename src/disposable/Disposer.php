<?php

namespace vr\core\disposable;


use Throwable;
use yii\base\BaseObject;

/**
 * Class Disposer
 * @package vr\core\disposable
 */
class Disposer extends BaseObject
{
    /**
     * @param IDisposable $disposable
     * @param callable $todo
     * @throws Throwable
     */
    public static function using(IDisposable $disposable, callable $todo)
    {
        try {
            call_user_func($todo, $disposable);
        } catch (Throwable $throwable) {
            throw $throwable;
        } finally {
            $disposable->dispose();
        }
    }
}