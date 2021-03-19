<?php


namespace app\controllers;


use yii\web\Controller;

class MainController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
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