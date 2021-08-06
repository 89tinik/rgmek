<?php


namespace app\models;


use yii\base\Model;

class InvoiceSberForm extends Model
{
    public $ee;
    public $penalty;
    public $invoice;

    public function rules()
    {
        return [
            [['ee', 'penalty'], 'trim'],
            ['invoice', 'required']
        ];
    }


}