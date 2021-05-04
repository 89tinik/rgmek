<?php


namespace app\models;


use yii\base\Model;

class InstallESForm extends Model
{
    public $email;
    public $user;

    public function rules()
    {
        return [
            [['email', 'user'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }


}