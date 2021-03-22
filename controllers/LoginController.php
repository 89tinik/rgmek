<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\RegisterForm;
use yii\web\Controller;
use Yii;

class LoginController extends Controller
{
    public $layout = 'login';

    public function actionIndex()
    {
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goHome();
        }

        $loginForm->password = '';


        return $this->render('index', compact('loginForm'));
    }

    public function actionRegistration()
    {
        $registerForm = new RegisterForm();

        if ($registerForm->load(Yii::$app->request->post())) {

            if ($registerForm->validate() ) {
                if ($registerForm->Registr()) {
                    Yii::$app->session->setFlash('success', 'Регистрация завершена');
                   // return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка регистрации!!!');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!');
            }
        }

        return $this->render('registration', compact('registerForm'));
    }
}