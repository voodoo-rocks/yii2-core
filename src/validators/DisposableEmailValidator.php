<?php


namespace vr\core\validators;


use RuntimeException;
use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * Class DisposableEmailValidator
 * @package vr\core\validators
 */
class DisposableEmailValidator extends Validator
{
    /**
     * @var array
     */
    public $domainsBlacklist = [
        'oolloo.org'
    ];

    /**
     * @var string
     */
    public $message = 'The email address does not look real';

    /**
     * @param mixed $value
     * @return array|null
     * @throws InvalidConfigException
     */
    protected function validateValue($value)
    {
        $checker = Yii::$app->get('disposable-email-checker', false);
        if (!$checker) {
            throw new RuntimeException('Component disposable-email-checker not found. Please add it as an object of vr\core\components\DisposableEmailChecker to config/web.php');
        }

        $disposable = $checker->check($value);
        return ($disposable || $this->isInBlacklist($value)) ? [$this->message, []] : null;
    }

    /**
     * @param string $email
     * @return bool
     */
    private function isInBlacklist(string $email)
    {
        $explode = explode('@', $email);
        $domain  = array_pop($explode);

        return in_array($domain, $this->domainsBlacklist);
    }
}