<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 17/04/2018
 * Time: 09:52
 */

namespace vr\core;

use yii\base\UserException;

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

        foreach ($errors as $attribute => $attributeErrors) {

            if ($preserveAttributes) {
                $messages[] = implode(' : ', [
                    'attribute' => $attribute,
                    'message'   => implode(', ', (array)$attributeErrors),
                ]);
            } else {
                $messages[] = implode(', ', (array)$attributeErrors);
            }
        }

        parent::__construct(implode(PHP_EOL, $messages));
    }
}