<?php


namespace app\models;


use pantera\yii2\pay\sberbank\models\Invoice;
use yii\db\ActiveRecord;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class Receipt extends ActiveRecord
{
    public static function tableName()
    {
        return 'receipt'; // TODO: Change the autogenerated stub
    }



    public function getInvoice()
    {
        $this->hasOne(Invoice::class, ['order_id' => 'id']);
    }

    public function sendToServer(){
        $user = User::findOne($this->user_id);
        $data['number']=$this->id;
        $data['id']=$user->id_db;
        $data['uid']=$this->contract;
        $data['electricity_debt']=$this->ee;
        $data['current_penalty']=$this->penalty;
        $data['date']=date("Y-m-d H:i:s");
        $dataSend['pay_documents'][]=$data;
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/creat_pay_documents')
            ->setData($dataSend)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $responseArr = $xml->parse($response);
            if (!empty($responseArr['Successful']) || !empty($responseArr['Error']['UIDPayDoc'])){
                if($this->setStatus('send', $responseArr['Successful']['Number'], $responseArr['Successful']['UIDPayDoc'] )){
                    if ($responseArr['Error']['UIDPayDoc']) {
                        \Yii::$app->session->setFlash('receiptUID',$responseArr['Error']['UIDPayDoc']);
                    } else {
                        \Yii::$app->session->setFlash('receiptUID',$responseArr['Successful']['UIDPayDoc']);
                        \Yii::$app->session->setFlash('receiptN1C',$responseArr['Successful']['NumberPayDoc']);
                    }
                    return true;
                }
            } else {
                $error = print_r($responseArr['Error'], true);
                \Yii::warning('Не удалось добавить запись в 1С - '. $error);
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }

    }


    public function setStatus($status, $receiptId=NULL, $uid=NULL){
        if(!empty($receiptId)) {
            $receipt = self::findOne($receiptId);
        } else {
            $receipt = $this;
        }

        if(!empty($uid)){
            $receipt->uid = $uid;
        }
        $receipt->status = $status;
        return $receipt->save();
    }

    public function getUser()
    {
        $this->hasOne(User::class, ['id' => 'user_id']);
    }
}