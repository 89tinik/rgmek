<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\VerificationForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

class LoginController extends Controller
{
    public $layout = 'login';


    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
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
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $registerFormArr = $this->generateForm(['error'=>'Ошибка регистрации!!!']);
        $registerForm = $registerFormArr['form'];
        $kpp = $registerFormArr['kpp'];

        return $this->render('registration', compact('registerForm', 'kpp'));
    }

    public function actionRepassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $registerFormArr = $this->generateForm(['error'=>'Ошибка регистрации!!!']);
        $registerForm = $registerFormArr['form'];
        $kpp = $registerFormArr['kpp'];
        return $this->render('repassword', compact('registerForm', 'kpp'));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login');
    }

    public function actionVerification()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $verificationForm = new VerificationForm();
        if ($verificationForm->load(Yii::$app->request->post())) {
            $activationResult = $verificationForm->activate();
            if ($activationResult['uName']) {
                $urlArr = explode('/', Yii::$app->request->referrer);
                $prevAction = end($urlArr);
                $messageF='';
                if ($prevAction == 'registration'){
                    $messageF = 'Регистрация завершена. ';
                } elseif ($prevAction == 'repassword'){
                    $messageF = 'Пароль успешно изменён. ';
                }
                Yii::$app->session->setFlash('success', $messageF.'Логин для входа <b>' . $activationResult['uName'] . '</b>.');
                return $this->redirect('/login');
            } else {
                Yii::$app->session->setFlash('error', $activationResult['error']);
            }
        }
        return $this->render('verification', compact('verificationForm'));
    }

    protected function generateForm($message=array()){
        $registerForm = new RegisterForm();
        $kpp = false;
        if ($registerForm->load(Yii::$app->request->post())) {

            if ($registerForm->validate()) {
                $register = $registerForm->Registr();
                if ($register['uMethod']) {
                    Yii::$app->session->setFlash('success', 'Подтвердите Ваши котактные данные. Введите проверочный код отправленый на указанный Вами ' . $register['uMethod'] . '.');
                    return $this->redirect('/verification');
                } elseif ($register['error'] == 501) {
                    $kpp = true;
                    Yii::$app->session->setFlash('error', 'Ваш ИНН не уникален - введите КПП.<br/>');
                } else {
                    Yii::$app->session->setFlash('error', $message.'<br/>' . $register['error']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!');
            }
        }
        // var_dump($registerForm->method);die();
        if (is_null($registerForm->method)) {
            $registerForm->method = 0;
        }
        if (!is_null($registerForm->kpp) || $kpp) {
            $output['kpp'] = true;
        } else {
            $output['kpp'] = false;
        }

        $output['form'] = $registerForm;
        return $output;
    }
}