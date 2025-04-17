<?php


namespace app\modules\admin\controllers;


use app\models\User;
use app\modules\admin\models\Admin;
use pantera\yii2\pay\sberbank\models\Invoice;
use Yii;
use yii\httpclient\Client;
use yii\web\Controller;


class AjaxController extends Controller
{

    //public $layout = 'ajax';

    public function actionAddInvoiceToOneC()
    {

        $data = Yii::$app->request->post();
        $currentInvoice = Invoice::findOne($data['invoice']);
        $oldStatus = $currentInvoice->status;
        $currentInvoice->status = 'I';
        $user = User::findOne($currentInvoice->user_id);
        if ($user && $currentInvoice->save(false)){
            $adminId = Yii::$app->user->id;
            $user->setDataContracts();
            if (Yii::$app->user->login($user, 3600 * 24 * 30 * 12)){
                Admin::setSessionAdmin($adminId);
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/background_task')
                    ->setData(['id' => $user->id_db])
                    ->send();
                $log_p = print_r($response, 1);
               // \Yii::error('ответ с проводки в 1с:'.$log_p);
            }
            return $this->redirect(['/sberbank/default/complete', 'orderId'=>$currentInvoice->mdOrder,'lang'=>'ru']);
        }
        ///sberbank/default/complete?orderId=f7579a2b-fd8a-784c-813e-e5cd02aef919&lang=ru
       // return $this->redirect(['/admin/baner', ['er'=>'gf']]);
//        return $currentInvoice->orderId;
    }


}
