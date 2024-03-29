<?php


namespace app\modules\admin\models;


use app\models\User;
use yii\base\Model;

class UpdateForm extends Model
{
    public $username;
    public $password;
    public $blocked;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'filter' => ['not', ['id' => \Yii::$app->request->post('UpdateForm')['user_id']]],],
            ['user_id', 'integer'],
            ['blocked', 'boolean'],
            ['password', 'match', 'pattern' => '#[a-z]#is', 'message' => 'Пароль должен содержать минимум 1 английскую букву'],
            ['password', 'match', 'pattern' => '/^[A-Za-z0-9]+$/', 'message' => 'Пароль должен содержать только цифры и английские буквы'],
            ['password', 'string', 'min' => '6', 'message' => 'Пароль должен содержать не менее 6-ти символов'],
            //[['phone','email'], 'unique', 'targetClass'=>'app/models/User'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'username' => 'Логин',
            'blocked' => 'Заблокирован',
        ]; // TODO: Change the autogenerated stub
    }

    public function updateUser()
    {
        $user = User::findOne($this->user_id);
        if ($user->username != $this->username) {
            $user->username = $this->username;
        }
        if (!empty($this->password)) {
           $user->password = \Yii::$app->security->generatePasswordHash(mb_strtolower($this->password, 'UTF-8'));
        }
        $user->blocked = $this->blocked;
        return $user->save();
    }
}