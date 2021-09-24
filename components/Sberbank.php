<?php


namespace app\components;


use app\models\Receipt;
use pantera\yii2\pay\sberbank\models\Invoice;
use yii\helpers\Url;

class Sberbank extends \pantera\yii2\pay\sberbank\components\Sberbank
{
    public function create(Invoice $model, array $post = [])
    {
        $reciept = Receipt::findOne($model->order_id);
        $post['orderNumber'] = $model->data['uniqid'];
        $post['amount'] = $model->sum * 100;
        $post['returnUrl'] = Url::to($this->returnUrl, true);
        $post['sessionTimeoutSecs'] = $this->sessionTimeoutSecs;

        $cart = [];
        $position = 1;
//        var_dump($reciept->ee * 100);
//        var_dump($reciept->penalty * 100);
//        var_dump($model->sum * 100);
//        var_dump(($reciept->ee * 100 + $reciept->penalty * 100) == $model->sum * 100);
        if (!empty($reciept->ee)) {
            $cart[] = array(
                'positionId' => $position,
                'name' => 'Электроэнергия',
                'quantity' => array(
                    'value' => 1,
                    'measure' => 'шт'
                ),
                'itemAmount' => intval(1 * ($reciept->ee * 100)),
                'itemCode' => 'ee',
                'itemPrice' => intval($reciept->ee * 100),
            );
            $position++;
        }

        if (!empty($reciept->penalty)) {
            $cart[] = array(
                'positionId' => $position,
                'name' => 'Пени',
                'quantity' => array(
                    'value' => 1,
                    'measure' => 'шт'
                ),
                'itemAmount' => intval(1 * ($reciept->penalty * 100)),
                'itemCode' => 'penalty',
                'tax' => array(
                    'taxType' => 0
                ),
                'itemPrice' => intval($reciept->penalty * 100),

            );
        }

        $post['orderBundle'] = json_encode(
            array(
                'cartItems' => array(
                    'items' => $cart
                )
            ),
            JSON_UNESCAPED_UNICODE
        );


        if (array_key_exists('comment', $model->data)) {
            $post['description'] = $model->data['comment'];
        }
        $result = $this->send($this->classRegister->getActionRegister(), $post);
        if (array_key_exists('formUrl', $result)) {
            $model->url = $result['formUrl'];
            $model->save();
        }
        return $result;
    }
}