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
    public function actionListInvoice()
    {
        $data = \Yii::$app->request->post();
        $data['quantity'] = 'full';
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listInvoice', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
    public function actionTransfer()
    {
        $data = \Yii::$app->request->post();
        $data['tu'] = json_decode($data['tu'], true);
//        $data=[
//            "id"=>"c222afaaff-9e30-11e4-9c77-001e8c2d263f",
//            "uidcontract"=>"b95aa4a7-9f5e-11e4-9c77-001e8c2d263f",
//            "tu"=>[
//                0=>[
//                    "uidtu"=>"a383457f-19a8-41bd-99af-44c19f7afdb3",
//                     "indications"=>10000
//                ],
//                1=>[
//                    "uidtu"=>"8907550c-9e9a-11e4-9c77-001e8c2d263f",
//                    "indications"=>12000
//                ]
//            ]
//        ];
        //var_dump($data);
       // die();
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/send_indication')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $responseArr = $xml->parse($response);
            if (isset($responseArr['Error'])){
                return $responseArr['Error']['Message'];
            } else {
                return 'Ваши данные успешно переданны!';
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }

    }
}
