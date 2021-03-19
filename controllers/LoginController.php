<?php


namespace app\controllers;


use yii\web\Controller;

class LoginController extends Controller
{
    public $layout = 'login';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegistration()
    {
        return $this->render('registration');
    }
}