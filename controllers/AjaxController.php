<?php


namespace app\controllers;


use app\models\User;
use yii\web\Controller;
use Yii;

class AjaxController extends Controller
{

    public $layout = 'ajax';

    public function actionReSendVerification()
    {
        $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
        $outputArr = [];
        if ($user){
            if($send = $user->sendVerification() === true){
                $uMethod = (Yii::$app->session->get('vMethod')==1) ? 'телефон' : 'e-mail';
                $outputArr['success'] = 'Проверьте Ваш ' . $uMethod . '.';
            } else {
                $outputArr['error'] =  $send['error'];
            }
        } else {
            $outputArr['error'] = 'Ваша сессия просрочена - заполните предыдущую форму заново.';
        }
        return json_encode($outputArr);
    }
}
