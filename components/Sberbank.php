<?php


namespace app\components;


use pantera\yii2\pay\sberbank\models\Invoice;
use yii\helpers\Url;

class Sberbank extends \pantera\yii2\pay\sberbank\components\Sberbank
{
    public function create(Invoice $model, array $post = [])
    {
        $post['orderNumber'] = $model->data['uniqid'];
        $post['amount'] = $model->sum * 100;
        $post['returnUrl'] = Url::to($this->returnUrl, true);
        $post['sessionTimeoutSecs'] = $this->sessionTimeoutSecs;



        $cart = array(
            array(
                'positionId' => 1,
                'name' => 'Оплата из ЛК',
                'quantity' => array(
                    'value' => 1,
                    'measure' => 'шт'
                ),
                'itemAmount' => 1 * ($model->sum * 100),
                'itemCode' => 'ee-penalty',
                'tax' => array(
                    'taxType' => 0
                ),
                'itemPrice' => $model->sum * 100,
            )
        );

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