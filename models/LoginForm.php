<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['username', 'trim'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return ['username'=>'Логин', 'password'=>'Пароль']; // TODO: Change the autogenerated stub
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword(mb_strtolower($this->password, 'UTF-8'))) {
                $this->addError($attribute, 'Неверно введен "Логин" или "Пароль".');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
//            $client = new Client();
//            $client->createRequest()
//                ->setMethod('GET')
//                ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/background_task')
//                ->setData(['id'=>$user->id_db])
//                ->send();


            $contracts = new Client();
            $response = $contracts->createRequest()
                ->setMethod('GET')
                ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
                ->setData([
                    'id' => $user->id_db
                ])
                ->send();
            if ($response->isOk) {
                $xml = new XmlParser();
                $result = $xml->parse($response);
                if ($result['Contract']) {
                    Contract::updateAllContract($user, $result['Contract']);
                    $user->with_date = $result['Withdate'];
                    $user->by_date = $result['Bydate'];
                    $user->full_name = $result['Name'];
                    $user->save();
                } else {
                    Contract::removeAllUserContract($user->id);
                }
            } else {
                Yii::error('Не удалось связаться БД - повторите попытку позже.');
            }

            return Yii::$app->user->login($user, 36);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(['username'=> $this->username]);
        }

        return $this->_user;
    }
}
