<?php


namespace app\controllers;


use app\models\Contract;
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
                Yii::$app->session->setFlash('login', $activationResult['uName']);
                Yii::$app->session->remove('vCode');
                Yii::$app->session->remove('uId');
                Yii::$app->session->remove('vMethod');
                Yii::$app->session->remove('contact');
                return $this->redirect('/login');
            } else {
                Yii::$app->session->setFlash('error', $activationResult['error']);
            }
        } else {
            $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
            if ($user) {
                $send = $user->sendVerification();
                if ($send  === true) {
                    if (Yii::$app->session->get('vMethod') == 1) {
                        Yii::$app->session->setFlash('title', 'Введите код из SMS');
                        Yii::$app->session->setFlash('message', 'Код отправлен на номер ' . Yii::$app->session->get('contact') . '<br/>Его нужно использовать в течение 10 минут');
                    } else {
                        Yii::$app->session->setFlash('title', 'Введите код из письма');
                        Yii::$app->session->setFlash('message', 'Код отправлен на e-mail ' . Yii::$app->session->get('contact') . '<br/>Его нужно использовать в течение 10 минут');
                    }
                } else {
                    Yii::$app->session->setFlash('title', 'Ошибка');
                    Yii::$app->session->setFlash('error', $send['error']);
                }
            } else {
                if ($prevAction == 'registration') {
                    $messageF = 'регистрации';
                } elseif ($prevAction == 'repassword') {
                    $messageF = 'восстановления пароля';
                }
                Yii::$app->session->setFlash('error', 'Ваша сессия просрочена - заполните форму ' . $messageF . ' заново.');
            }
        }
        return $this->render('verification', compact('verificationForm'));
    }

    public function actionInformation()
    {
        return $this->render('information');
    }

    public function actionRemove()
    {
        $user = User::findOne(['id_db' => Yii::$app->request->get('id')]);
        Contract::deleteAll(['user_id'=>$user->id]);
        return $user->remove();
    }

    public function actionAll()//удалить после разработки
    {
        var_dump(User::showAll());
        die();
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
                    if (is_array($register['error'])){
                        $message = $register['error']['Message'];
                    } else {
                        $message = $register['error'];
                    }
                    $message = ($type == 'new') ? 'Ошибка регистрации!!!' . '<br/>' . $message : 'Ошибка восстановления пароля!!!' . '<br/>' . $message;
                    Yii::$app->session->setFlash('error', $message);
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