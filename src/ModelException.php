<?php
/**
 * @copyright Copyright (c) 2013-2016 Voodoo Mobile Consulting Group LLC
 * @link      https://voodoo.rocks
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace vr\core;

use yii\base\Model;
use yii\base\UserException;

/**
 * Class ModelException
 * @package    vr\core
 * @deprecated Use [[ErrorsException]] instead
 */
class ModelException extends UserException
{
    /**
     * @param Model    $entity
     * @param bool|int $preserveAttributes
     */
    public function __construct(Model $entity, $preserveAttributes = YII_DEBUG)
    {
        $messages = [];

        foreach ($entity->errors as $attribute => $errors) {

            if ($preserveAttributes) {
                $messages[] = implode(' : ', [
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