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
        return $this->render('index');
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