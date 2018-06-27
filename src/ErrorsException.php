<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27/06/2018
 * Time: 14:19
 */

namespace vr\core;

/**
 * Class ErrorsException
 * @package vr\core
 */
class ErrorsException extends UserException
{
    /**
     * @param array    $errors
     * @param bool|int $preserveAttributes
     */
    public function __construct(array $errors, $preserveAttributes = YII_DEBUG)
    {
        $messages = [];

        foreach ($errors as $attribute => $errors) {

            if ($preserveAttributes) {
                $messages[] = implode(': ', [
                    'attribute' => $attribute,
                    'message'   => implode(', ', (array)$errors),
                ]);
            } else {
                $messages[] = implode(', ', (array)$errors);
            }
        }

        parent::__construct(implode(PHP_EOL, $messages));
    }
}