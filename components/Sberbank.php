<?php


namespace app\components;


use app\models\Receipt;
use app\models\User;
use pantera\yii2\pay\sberbank\models\Invoice;
use yii\helpers\Json;
use yii\helpers\Url;

class Sberbank extends \pantera\yii2\pay\sberbank\components\Sberbank
{
    /**
     * @var string Адрес платежного шлюза
     */
    public $url = 'https://ecommerce.sberbank.ru/ecomm/gw/partner/api/v1/';
    /**
     * @var string Тестовый адрес платежного шлюза
     */
    public $urlTest = 'https://ecomtest.sberbank.ru/ecomm/gw/partner/api/v1/';
    /**
     * @var string Ашион сбербанка для получения статуса оплаты
     */
    public $actionStatus = 'getOrderStatusExtended.do';
    public function create(Invoice $model, array $post = [])
    {
        $reciept = Receipt::findOne($model->order_id);
        $user = User::findOne($model->user_id);
        $post['orderNumber'] = $model->data['uniqid'];
        $post['amount'] = (int)round($model->sum * 100, 0);
        $post['currency'] = "643";
        $post['features'] = "FORCE_SSL";
        $post['failUrl'] = Url::to($this->returnUrl, true);
        $post['returnUrl'] = Url::to($this->returnUrl, true);
        $post['sessionTimeoutSecs'] = $this->sessionTimeoutSecs;
        $post['clientId'] = "$model->user_id";

        $cart = [];
        $position = 1;

        if (!empty($reciept->ee)) {
            $cart[] = array(
                'positionId' => "$position",
                'name' => 'Электроэнергия',
                'quantity' => array(
                    'value' => 1
                ),
                'measurementUnit' => 'шт',
                'itemAmount' => (int)round(1 * ($reciept->ee * 100), 0),
                'itemCode' => 'ee',
                'itemPrice' => (int)round($reciept->ee * 100, 0),
                'paymentMethod' => 'full_payment',
                'paymentObject' => 'service',
                'tax' => array('taxType' => 6),
            );
            $position++;
        }

        if (!empty($reciept->penalty)) {
            $cart[] = array(
                'positionId' => "$position",
                'name' => 'Пени',
                'quantity' => array(
                    'value' => 1
                ),
                'measurementUnit' => 'шт',
                'itemAmount' => (int)round(1 * ($reciept->penalty * 100), 0),
                'itemCode' => '2',
                'itemPrice' => (int)round($reciept->penalty * 100, 0),
                'paymentMethod' => 'full_payment',
                'paymentObject' => 'service',
                'tax' => array('taxType' => 6),

            );
        }

        $post['orderBundle'] =
            [
                "payments" => [
                    ["type"=> 1,
                        "sum"=> (int)round($model->sum * 100, 0)]
                ],
                'ffdVersion' => '1.05',
                'receiptType' => 'sell',
                'total' => (int)round($model->sum * 100, 0),
                'company' => [
                    "email" => "info@rgmek.ru",
                    "sno" => "osn",
                    "inn" => "6229054695",
                    "paymentAddress" => "http://lk.rgmek.ru"
                ],
                'cartItems' => [
                    'items' => $cart
                ]
            ];
        if (isset($user->phone) && !empty($user->phone)) {
            $post['phone'] = '+7' . substr($user->phone, 1);
        }
        if (isset($user->email) && !empty($user->email)) {
            $post['email'] = $user->email;
        }
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

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>Json::encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_HEADER => true, // включаем заголовки в вывод
            CURLINFO_HEADER_OUT => true, // включаем сохранение заголовков запроса
        ));

        $out = curl_exec($curl);
        $info = curl_getinfo($curl);
        $out = substr($out, $info['header_size']);

        $debugSber = false;
        if ($debugSber) {
            // Получение заголовков запроса
            $requestHeaders = 'ЗАГОЛОВКИ ЗАПРОСА:' . print_r($info['request_header'], true);
            $curlI = 'ИНФОРМАЦИЯ CURL:' . print_r($info, true);

            $header = substr($out, 0, $info['header_size']);

            $deb_url = 'АДРЕСС:' . print_r($url, true);
            $deb_data = 'ДАННЫЕ:' . print_r(Json::encode($data), true);
            $deb_out = 'ОТВЕТ:' . print_r($out, true);

            $somecontent = date("F j, Y, g:i a") . "\n" .
                $deb_url . "\n" .
                $requestHeaders . "\n" .
                $deb_data . "\n" .
                'ЗАГОЛОВКИ ОТВЕТА:' . print_r($header, true) . "\n" .
                $deb_out . "\n" .
                $curlI . "\n\n\n\n\n\n";


            $fp = fopen("sber.log", "a");
            fwrite($fp, $somecontent);
            fclose($fp);
        }
        if ($out) {
            curl_close($curl);
            return Json::decode($out);
        } else {
            $dataMess = print_r($data, true);
           // \Yii::$app->mailer->compose()
           //     ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
           //     ->setTo(['89.tinik@gmail.com','mapurian@gmail.com','kai@rgmek.ru'])
           //     ->setSubject('Ошибка при переходе на шлюз Сбера!!!')
           //     ->setHtmlBody('Время ошибки:' . date("Y-m-d H:i:s") . '.<br/> Данные:<br>' . $dataMess . '<br/>Ошибка curl: ' . curl_error($curl))
           //     ->send();
                
            curl_close($curl);
            die('Ошибка');
        }
    }
}
