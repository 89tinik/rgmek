<?php

namespace app\controllers;

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

    protected function sendToServer ($url, $data=array(), $toArray=true, $method='GET'){
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setData($data)
            ->send();
        if ($response->isOk) {
            if ($toArray){
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            }else{
                return ['success' => $response];
            }
        } else {
            $this->redirect(['err/one-c']);
        }
    }
}