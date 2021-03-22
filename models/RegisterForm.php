<?php


namespace app\models;


use yii\base\Model;

class RegisterForm extends Model
{

    public $inn;
    public $password;
    public $rePassword;
    public $contract;
    public $email;
    public $phone;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['inn', 'contract', 'password', 'rePassword'], 'required'],
            ['rePassword', 'compare', 'compareAttribute' => 'password'],
            ['email', 'email'],
            ['phone', 'trim'],
        ];
    }

    public function Registr(){
        $user = new User();
        if (!empty($this->email)){
            $user->username = $this->email;
            $user->email = $this->email;
        } elseif (!empty($this->phone)){
            $user->username = $this->phone;
            $user->phone = $this->phone;
        }
        if (!empty($user->username)){
            $user->inn = $this->inn;
            $user->contract = $this->contract;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            return $user->save();
        }
    }
}