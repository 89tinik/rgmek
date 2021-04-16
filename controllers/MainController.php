<?php


namespace app\controllers;


use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\filters\AccessControl;

class MainController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest){
            $user = 'гость';
        } else {
            $user = 'негость';
        }
        return $this->render('index', compact('user'));
    }

    public function actionProfile()
    {
        $uId = \Yii::$app->user->identity->id_db;
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/profile')
            ->setData([
                'id' => $uId
            ])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);
			//var_dump($result);
			//die();
            return $this->render('profile', compact('result'));
        } else {
            return 'Не удалось связаться БД - повторите попытку пзже.';
        }

    }

    public function actionPayment()
    {
        return $this->render('payment');
    }
}