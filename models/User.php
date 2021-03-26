<?php

namespace app\models;

use linslin\yii2\curl\Curl;
use yii\db\ActiveRecord;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

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

    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    public function setIdDb()
    {
        $this->id_db = md5($this->username);
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
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            //->setUrl('http://pushkin.studio/testrgmekru/test.xml')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);

            if ($result['Error']) {
                return ['error' => $result['Error']['Message']];
            } else {
                return ['success' => $result['Value']];
            }
        } else {
            return ['error' => 'Не удалось связаться БД - повторите попытку пзже.'];
        }


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
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

}
