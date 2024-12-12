<?php

namespace app\controllers;

use SimpleXMLElement;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;

class BaseController extends Controller
{
    public $layout = 'inner';
    public $userName = '';
    public $listContract = '';
    public $piramida = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (isset(\Yii::$app->user->identity->full_name)) {
            $this->userName = \Yii::$app->user->identity->full_name;

            if (!empty(\Yii::$app->user->identity->peramida_name)) {
                $this->piramida = ['name' => \Yii::$app->user->identity->peramida_name, 'id' => \Yii::$app->user->identity->session_id];
            }
        }
        return parent::beforeAction($action);
    }

    protected function sendToServer($url, $data = array(), $toArray = true, $method = 'GET', $xmlData = false)
    {
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url);
        if ($xmlData) {
            $request->setHeaders(['Content-Type' => 'application/xml; charset=utf-8'])
                ->setContent($data);
        } else {
            $request->setData($data);
        }
        $response = $request->send();
        if ($response->isOk) {
            if ($toArray) {
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            } else {
                return ['success' => $response];
            }
        } else {
            $this->redirect(['err/one-c']);
        }
    }

    protected function arrayToXml(array $data, SimpleXMLElement &$xmlData)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars($value));
            }
        }
    }
}