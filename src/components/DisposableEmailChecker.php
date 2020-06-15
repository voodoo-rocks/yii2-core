<?php


namespace vr\core\components;

use GuzzleHttp\Client;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class DisposableEmailChecker
 * @package vr\core\components
 */
class DisposableEmailChecker extends BaseObject
{
    /**
     * @var
     */
    public $apiKey;

    /**
     * @var string
     */
    public $url = 'http://api.nameapi.org/rest/v5.3/email/';

    /**
     * @param $email
     * @return mixed
     * @throws HttpException
     */
    public function check($email)
    {
        $client = new Client([
            'base_uri' => $this->url,
        ]);

        $response = $client->get('disposableemailaddressdetector', [
            'query' => [
                'apiKey'       => $this->apiKey,
                'emailAddress' => $email
            ]
        ]);

        if (($code = $response->getStatusCode()) != 200) {
            throw new HttpException($code, $response->getReasonPhrase());
        }

        $contents = $response->getBody()->getContents();
        return ArrayHelper::getValue(Json::decode($contents), 'disposable') == 'YES';
    }
}