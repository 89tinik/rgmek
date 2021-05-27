<?php


namespace app\controllers;


use app\models\User;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use Yii;

class AjaxController extends Controller
{

    public $layout = 'ajax';

    public function actionReSendVerification()
    {
        $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
        $outputArr = [];
        if ($user) {
            if ($send = $user->sendVerification() === true) {
                $uMethod = (Yii::$app->session->get('vMethod') == 1) ? 'телефон' : 'e-mail';
                $outputArr['success'] = 'Проверьте Ваш ' . $uMethod . '.';
            } else {
                $outputArr['error'] = $send['error'];
            }
        } else {
            $outputArr['error'] = 'Ваша сессия просрочена - заполните предыдущую форму заново.';
        }
        return json_encode($outputArr);
    }

    public function actionListPenalty()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_penalty')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listPenalty', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionListAktpp()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/act_reception_transfer')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listAktpp', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
    public function actionAccruedPaid()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/report_accrual_payment')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('accruedPaid', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
}
