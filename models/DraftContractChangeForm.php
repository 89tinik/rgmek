<?php

namespace app\models;

use yii\base\Model;

class DraftContractChangeForm extends Model
{
    public $id;
    public $contract_id;
    public $user_id;

    public $contract_price;
    public $contract_volume;
    public $contract_price_new;
    public $contract_volume_new;
    public $contract_volume_plane_include;

    public $director_full_name;
    public $director_position;
    public $director_order;

    public function rules()
    {
        return [
            [['id', 'user_id', 'contract_volume_plane_include'], 'integer'],
            [['contract_price', 'contract_volume', 'contract_price_new', 'contract_id', 'contract_volume_new'], 'string'],
            [['user_id', 'director_full_name','director_position', 'director_order'], 'required'],
            [['director_full_name','director_position', 'director_order'], 'string', 'max' => 255],
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
            'contract_volume' => 'Объем контракта(договора), кВтч',
            'contract_price_new' => 'Новая цена контракта (договора), руб.',
            'contract_volume_new' => 'Новый объем контракта(договора), кВтч',
            'contract_volume_plane_include' => 'Включать планируемый объем в контракт',
            'director_full_name' => 'ФИО руководителя (подписанта)*',
            'director_position' => 'Должность руководителя (подписанта)*',
            'director_order' => 'Действует на основании*',
        ];
    }

}
