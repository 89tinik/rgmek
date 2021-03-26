<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\RegisterForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

class LoginController extends Controller
{
    public $layout = 'login';


    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goHome();
        }

        $loginForm->password = '';


        return $this->render('index', compact('loginForm'));
    }

    public function actionRegistration()
    {
        if (!\Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $registerForm = new RegisterForm();
        $kpp = false;
        if ($registerForm->load(Yii::$app->request->post())) {

            if ($registerForm->validate() ) {
                $register = $registerForm->Registr();
                if ($register['uName']) {
                    Yii::$app->session->setFlash('success', 'Регистрация завершена. Логин для входа <b>'.$register['uName'].'</b>.');
                    return $this->redirect('/login');
                } elseif($register['error'] == 501){
                    $kpp = true;
                    Yii::$app->session->setFlash('error', 'Ваш ИНН не уникален - введите КПП.<br/>');
                }else{
                    Yii::$app->session->setFlash('error', 'Ошибка регистрации!!!<br/>'.$register['error']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!');
            }
        }

        return $this->render('registration', compact('registerForm','kpp'));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login');
    }
}