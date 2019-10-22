<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 27/06/2018
 * Time: 14:19
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
     * @var array
     */
    public $data;

    /**
     * @param array    $errors
     * @param int      $code
     * @param bool|int $preserveAttributes
     */
    public function __construct(array $errors, $code = 0, $preserveAttributes = YII_DEBUG)
    {
        $messages = [];

        foreach ($errors as $attribute => $attributeErrors) {
            if ($preserveAttributes) {
                $messages[] = implode(': ', [
                    'attribute' => $attribute,
                    'message'   => implode(', ', $attributeErrors),
                ]);
            } else {
                $messages[] = implode(', ', $attributeErrors);
            }
        }

        $this->data = $errors;

        parent::__construct(implode(PHP_EOL, $messages), $code);
    }
}