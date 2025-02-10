<?php

namespace app\models;

use linslin\yii2\curl\Curl;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use Yii;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
//    public $id;
//    public $username;
//    public $password;
//    public $authKey;
//    public $accessToken;
//
//    private static $users = [
//        '100' => [
//            'id' => '100',
//            'username' => 'admin',
//            'password' => 'admin',
//            'authKey' => 'test100key',
//            'accessToken' => '100-token',
//        ],
//        '101' => [
//            'id' => '101',
//            'username' => 'demo',
//            'password' => 'demo',
//            'authKey' => 'test101key',
//            'accessToken' => '101-token',
//        ],
//    ];

    public static function tableName()
    {
        return 'users'; // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'contract' => 'Контракт',
            'full_name' => 'Название',
            'blocked' => 'Заблокирован',
        ];
    }
    public function setPassword($password)
    {
        $this->temp = \Yii::$app->security->generatePasswordHash($password);
    }

    public function setIdDb()//удалить
    {
        $newIdDb = base64_encode($this->username);
        if ($newIdDb == $this->id_db) {
            $uNameArr = explode('-', base64_decode($this->id_db));
            if (isset($uNameArr[1])) {
                $newIdDb = base64_encode($uNameArr[1] . '-' . $uNameArr[0]);
            } else {
                $newIdDb = base64_encode($uNameArr[0] . '-' . $this->id);
            }

        }
        $this->id_db = $newIdDb;
    }

    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function validateFromDB($method)
    {
        $data = [
            'id' => $this->id_db,
            'inn' => $this->inn,
            'contract' => $this->contract,
            'method' => $method
        ];
        if ($method == 1) {
            $data['value'] = $this->phone;
        } else {
            $data['value'] = $this->email;
        }
        if ($this->kpp) {
            $data['kpp'] = $this->kpp;
        }
        //return ['success' => 'типа провалидиравали'];
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);

            if ($result['Error']) {
                return ['error' => $result['Error']['Message']];
            } else {
                return ['success' => $result['Value'], 'ID' => $result['ID']];
            }
        } else {
            return ['error' => 'Не удалось связаться БД - повторите попытку пзже.'];
        }


    }

    static public function validateFromDBnew($data)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $xml->parse($response);

        } else {
            return ['error' => 'Не удалось связаться БД - повторите попытку пзже.'];
        }


    }

    public function setVerification($method)
    {
        Yii::$app->session->set('vCode', rand(1000, 9999));
        Yii::$app->session->set('uId', $this->id);
        Yii::$app->session->set('vMethod', $method);
        if ($method == 1) {
            Yii::$app->session->set('contact', substr_replace($this->phone, '****', 5, 4));
        } else {
            Yii::$app->session->set('contact', substr_replace($this->email, '****', 1, 4));
        }
        return true;
    }

    public function sendVerification()
    {
        //return true; закоментировать

        $vCode = Yii::$app->session->get('vCode');
        if (Yii::$app->session->get('vMethod') == 1) {
            //отправляем SMS
            $client = new Client();
            $phone = substr_replace($this->phone, '7', 0, 1);
            $username = '1a4a5f50fc';
            $password = 'e599316079';
            $data = [
                'msisdn' => $phone,
                'shortcode' => 'rgmek',
                'text' => $vCode
            ];

            $response = $client->createRequest()
                ->setMethod('POST')
                ->setHeaders(['Authorization' => 'Basic ' . base64_encode("$username:$password")])
                ->setUrl('https://target.t2.ru/api/v2/send_message')
                ->setData($data)
                ->send();

            if (!$response->isOk) {
                return ['error' => 'Не удалось отправить SMS - повторите попытку регистрации позже.'];
            } else {
                $responseArrContent = json_decode($response->content, true);
                if ($responseArrContent['status'] == 'error') {
                    return ['error' => 'Не удалось отправить SMS - повторите попытку регистрации позже.Error:' . $responseArrContent['reason']];
                }
            }
        } else {
            //отправляем почту
            $mail = Yii::$app->mailer->compose()
                ->setFrom('noreply@send.rgmek.ru')
                ->setTo($this->email)
                ->setSubject('Подтверждение почты')
                ->setHtmlBody('<h2>Добрый день!</h2><p>Вы получили настоящее письмо так как указали этот адрес электронной почты при регистрации в личном кабинете небытовых потребителей компании ООО «Р-Энергия».</p><p>Код подтверждения:<b>' . $vCode . '</b>.</p><p>Если Вы не отправляли запрос на регистрацию просто удалите это письмо.</p>')
                ->send();
            if (!$mail) {
                return ['error' => 'Не удалось отправить письмо - повторите попытку регистрации позже.'];
            }
        }
        return true;
    }

    public function activation()
    { //@return true or array('error'=>'error message')

        $data = ['id' => $this->id_db];

        Yii::error('id user = ' .$this->id_db);
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/activation')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);

            //var_dump($result);
            //die('00');
            if ($result['Error']) {
                return ['error' => $result['Error']['Message']];
            } else {
                $this->password = $this->temp;
                if ($this->save()) {
                    return true;
                } else {
                    return ['error' => 'Не удалось сохранить пароль!'];
                }
            }
        } else {
            //die('--');
            return ['error' => 'Не удалось связаться БД - повторите попытку регистрации позже.'];
        }
    }

    public function remove()
    {
        $data = ['id' => $this->id_db];
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/delete')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $this->delete();
        }
    }

    public static function showAll()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/accounts')
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $xml->parse($response);
        }

    }

    public function setDataContracts(){
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
            ->setData([
                'id' => $this->id_db
            ])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);
            if ($result['Contract']) {
                Contract::updateAllContract($this, $result['Contract']);
                $this->with_date = $result['Withdate'];
                $this->by_date = $result['Bydate'];
                $this->full_name = $result['Name'];
                if($result['LK']){
                    $this->peramida_name = $result['LK']['Name'];
                }else{
                    $this->peramida_name = '';
                }
                $this->save();
            } else {
                Contract::removeAllUserContract($this->id);
            }
        } else {
            Yii::error('Не удалось связаться БД - повторите попытку позже.');
        }
    }

    public function setPushId($pushId){
        //FOR PUSH
        $client = new Client();
        $oldUserIdArr = explode(',', $this->playersid);
        if (!in_array($pushId, $oldUserIdArr)){
            $oldUserIdArr[] = $pushId;
            $this->playersid = implode(',', $oldUserIdArr);
            $this->save();
        }
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/add_ids_push')
            ->setData([
                'id' => $this->id_db,
                'ids_push' => $pushId
            ])
            ->send();
        if (!$response->isOk) {
            Yii::error('Не удалось передать userID для push - повторите авторизацию позже.');
        }

    }

    public function setSessionId(){
        $this->session_id = \Yii::$app->security->generateRandomString();
        $this->save();
    }

    public function getContracts(){
        return $this->hasMany(Contract::class, ['user_id'=>'id']);
    }
    public function getInvoices(){
        return $this->hasMany(Invoice::class, ['user_id'=>'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!empty($this->password)) {
            return \Yii::$app->security->validatePassword($password, $this->password);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * @return array
     */
    public function getContractsList()
    {
        $contracts = $this->getContracts()->all();
        return ArrayHelper::map($contracts, 'id', 'number');
    }

}
