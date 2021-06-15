<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\User;
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

//        $registerForm = $this->generateForm(['error' => 'Ошибка регистрации!!!']);
        $registerForm = $this->generateFormNew('new');

        return $this->render('registration', compact('registerForm'));
    }

    public function actionRepassword()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        //$registerForm = $this->generateForm(['error' => 'Ошибка восстановления пароля!!!']);
        $registerForm = $this->generateFormNew('repass');

        return $this->render('repassword', compact('registerForm'));
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

        $urlArr = explode('/', Yii::$app->request->referrer);
        $prevAction = end($urlArr);

        $verificationForm = new VerificationForm();
        if ($verificationForm->load(Yii::$app->request->post())) {
            $activationResult = $verificationForm->activate();
            if ($activationResult['uName']) {
                $messageF = '';
                if ($prevAction == 'registration') {
                    $messageF = 'Регистрация завершена. ';
                } elseif ($prevAction == 'repassword') {
                    $messageF = 'Пароль успешно изменён. ';
                }
                Yii::$app->session->setFlash('success', $messageF . 'Логин для входа <b>' . $activationResult['uName'] . '</b>.');
                Yii::$app->session->remove('vCode');
                Yii::$app->session->remove('uId');
                Yii::$app->session->remove('vMethod');
                return $this->redirect('/login');
            } else {
                Yii::$app->session->setFlash('error', $activationResult['error']);
            }
        } else {
            $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
            if ($user){
                if($send = $user->sendVerification() === true){
                    $uMethod = (Yii::$app->session->get('vMethod')==1) ? 'телефон' : 'e-mail';
                    Yii::$app->session->setFlash('success','Подтвердите Ваши котактные данные. Введите проверочный код отправленый на указанный Вами ' . $uMethod . '.');
                } else {
                    Yii::$app->session->setFlash('error', $send['error']);
                }
            } else {
                if ($prevAction == 'registration') {
                    $messageF = 'регистрации';
                } elseif ($prevAction == 'repassword') {
                    $messageF = 'восстановления пароля';
                }
                Yii::$app->session->setFlash('error', 'Ваша сессия просрочена - заполните форму '.$messageF.' заново.');
            }
        }
        return $this->render('verification', compact('verificationForm'));
    }

    public function actionRemove()
    {
        $user = User::findOne(['id_db' => Yii::$app->request->get('id')]);

        return $user->remove();
    }
	
    public function actionAll()//удалить после разработки
    {
        var_dump(User::showAll());
		die();
    }
	
    protected function generateForm($message = array())
    {
        $registerForm = new RegisterForm();
        $kpp = false;
        if ($registerForm->load(Yii::$app->request->post())) {

            if ($registerForm->validate()) {
                $register = $registerForm->Registr();
                if ($register['uMethod']) {
                    $this->redirect('/verification');
                } elseif ($register['error'] == 501) {
                    $registerForm->setKPP();
                    Yii::$app->session->setFlash('error', 'Ваш ИНН не уникален - введите КПП.<br/>');
                } else {
                    Yii::$app->session->setFlash('error', $message['error'] . '<br/>' . $register['error']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!');
            }
        }
        // var_dump($registerForm->method);die();
        if (is_null($registerForm->method)) {
            $registerForm->method = 0;
        }
        if (!is_null($registerForm->kpp)) {
            $registerForm->setKPP();
        }

        return $registerForm;
    }
    protected function generateFormNew($type)
    {
        $registerForm = new RegisterForm();
        if ($registerForm->load(Yii::$app->request->post())) {

            if ($registerForm->validate()) {
                $register = $registerForm->Registrnew($type);
                if ($register['uMethod']) {
                    $this->redirect('/verification');
                } else {
                    $message = ($type== 'new')?'Ошибка регистрации!!!'. '<br/>' . $register['error']:'Ошибка восстановления пароля!!!'. '<br/>' . $register['error']['Message'];
                    Yii::$app->session->setFlash('error', $message );
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!');
            }
        }
        if (is_null($registerForm->method)) {
            $registerForm->method = 0;
        }

        return $registerForm;
    }
}