<?php

namespace app\models;

use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class ConsumptionForm extends Model
{
    public $uidtu;
    public $uid;
    public $uidobject;
    public $withdate;
    public $bydate;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['withdate', 'bydate'], 'required'],
            [['uidobject', 'uidtu', 'uid'], 'trim']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'withdate' => 'с',
            'bydate' => 'по'
        ];
    }


}