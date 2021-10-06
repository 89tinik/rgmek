<?php


namespace app\components;


use app\models\Receipt;
use pantera\yii2\pay\sberbank\models\Invoice;
use yii\helpers\Json;
use yii\helpers\Url;

class Sberbank extends \pantera\yii2\pay\sberbank\components\Sberbank
{
    public function create(Invoice $model, array $post = [])
    {
        $reciept = Receipt::findOne($model->order_id);
        $post['orderNumber'] = $model->data['uniqid'];
        $post['amount'] = (int)round($model->sum * 100, 0);
        $post['returnUrl'] = Url::to($this->returnUrl, true);
        $post['sessionTimeoutSecs'] = $this->sessionTimeoutSecs;

        $cart = [];
        $position = 1;

        if (!empty($reciept->ee)) {
            $cart[] = array(
                'positionId' => $position,
                'name' => 'Электроэнергия',
                'quantity' => array(
                    'value' => 1,
                    'measure' => 'шт'
                ),
                'itemAmount' => (int)round(1 * ($reciept->ee * 100), 0),
                'itemCode' => 'ee',
                'itemPrice' => (int)round($reciept->ee * 100, 0),
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
                'itemAmount' => (int)round(1 * ($reciept->penalty * 100), 0),
                'itemCode' => 'penalty',
                'tax' => array(
                    'taxType' => 0
                ),
                'itemPrice' => (int)round($reciept->penalty * 100, 0),

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

    /**
     * Откправка запроса в api сбербанка
     * @param $action string Акшион на который отпровляем запрос
     * @param $data array Параметры которые передаём в запрос
     * @return mixed Ответ сбербанка
     */
    public function send($action, $data)
    {
        $data = $this->insertAuthData($data);
        $url = ($this->testServer ? $this->urlTest : $this->url) . $action;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $out = curl_exec($curl);
        curl_close($curl);
        if ($out) {
            return Json::decode($out);
        } else {
            $dataMess = print_r($data, true);
            \Yii::$app->mailer->compose()
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo(['89.tinik@gmail.com','mapurian@gmail.com'])
                ->setSubject('Ошибка при переходе на шлюз Сбера!!!')
                ->setHtmlBody('Время ошибки:' . date("Y-m-d H:i:s") . '.<br/> Данные:<br>' . $dataMess . '<br/>Ошибка curl: ' . curl_error($out))
                ->send();
            die('Ошибка');
        }
    }
}