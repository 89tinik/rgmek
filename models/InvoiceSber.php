<?php


namespace app\models;


use pantera\yii2\pay\sberbank\models\Invoice;
use yii\base\InvalidParamException;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class InvoiceSber extends Invoice
{
    public static function addSberbank($orderId = null, $price, $remoteId = null, $data = [])
    {
        if (empty($orderId) && empty($remoteId)) {
            throw new InvalidParamException('Обязательно должен присутствовать идентификатор локального заказа или с удаленного сервиса');
        }
        $model = new self();
        $model->order_id = $orderId;
        $model->remote_id = $remoteId;
        $model->user_id = \Yii::$app->user->id;
        $model->method = 'SB';
        $model->sum = $price;
        $model->status = 'I';
        $model->data = $data;
        $model->save(false);
        return $model;
    }

    public static function sendToServer(Invoice $invoice){
        $data = $invoice->data;
        unset($data['uniqid']);
        $client = new Client();
//        $response = $client->createRequest()
//            ->setMethod('GET')
//            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration')
//            ->setData($data)
//            ->send();
//        if ($response->isOk) {
//            $xml = new XmlParser();
//            return $xml->parse($response);
//
//        } else {
//            return ['error' => 'Не удалось связаться БД - повторите попытку пзже.'];
//        }
    }
}