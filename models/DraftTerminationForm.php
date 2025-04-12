<?php

namespace app\models;

use yii\base\Model;

class DraftTerminationForm extends Model
{
    public $id;
    public $contract_id;
    public $user_id;

    public $contract_price;
    public $contract_volume_price;

    public $director_full_name;
    public $director_position;
    public $director_order;

    public function rules()
    {
        return [
            [['user_id', 'director_full_name', 'director_position', 'director_order'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['contract_price', 'contract_volume_price', 'contract_id'], 'string'],
            [['director_full_name', 'director_position', 'director_order'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'contract_id' => 'Контракт (договор)',
            'contract_price' => 'Цена контракта (договора), руб.',
            'contract_volume_price' => 'Стоимость электроэнергии, поставленной по контракту (договору), руб',
            'director_full_name' => 'ФИО руководителя (подписанта)*',
            'director_position' => 'Должность руководителя (подписанта)*',
            'director_order' => 'Действует на основании*',
        ];
    }
}
