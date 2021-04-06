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
    public $method;
    public $kpp;


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
            [['phone', 'method', 'kpp'], 'trim'],
            ['password', 'match', 'pattern' => '#[a-z]#is', 'message' => 'Пароль должен содержать минимум 1 английскую букву'],
            ['password', 'match', 'pattern' => '/^[A-Za-z0-9]+$/', 'message' => 'Пароль должен содержать только цифры и английские буквы'],
            ['password', 'string', 'min' => '6', 'message' => 'Пароль должен содержать не менее 6-ти символов'],
            //[['phone','email'], 'unique', 'targetClass'=>'app/models/User'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'inn'=>'ИНН',
            'contract'=>'Договор',
            'password'=>'Пароль',
            'rePassword'=>'Повторите пароль',
            'email'=>'E-mail',
            'phone'=>'Телефон',
        ]; // TODO: Change the autogenerated stub
    }

    public function Registr(){
        if (!empty($this->kpp)){
            $username = $this->inn.'-'.$this->kpp;
        } else {
            $username = $this->inn;
        }

        $user = User::findOne(['username'=>$username]);

        if (!$user){
            $user = new User();
        }

        $user->username = $username;
        if (!empty($user->username)){
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->inn = $this->inn;
            $user->contract = $this->contract;
            $user->kpp = $this->kpp;
            $user->setPassword( mb_strtolower($this->password, 'UTF-8'));
            $user->generateAuthKey();
            $user->setIdDb();
            $validate = $user->validateFromDB($this->method);

            if ($validate['success']){
                if ($user->save()){
                    $sendCode = $user->setVerification();
                    if ( $sendCode === true) {
                        $uMethod = (!empty($user->phone))?'телефон':'e-mail';
                        return ['uMethod' => $uMethod];
                    } else {
                        return $sendCode;
                    }
                } else {
                    return ['error'=>'Не удалось зарегистрироваться - повторите попытку позже.'];
                }
            } else {
                return ['error'=> $validate['error']];
            }

        }
    }
}