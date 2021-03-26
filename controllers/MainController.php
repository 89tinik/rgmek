<?php


namespace app\controllers;


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
        return $this->render('profile');
    }

    public function actionPayment()
    {
        return $this->render('payment');
    }
}