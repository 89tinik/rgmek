<?php


namespace app\models;


use yii\base\Model;

class ReceiptForm extends Model // устарело
{
    public $ee;
    public $penalty;
    public $contract;

    public function rules()
    {
        return [
            [['ee', 'penalty'], 'trim'],
            ['contract', 'required'],
            ['contract', 'noEmptyPay']
        ];
    }

    public function addReceipt(){
        if ($this->validate()){
            $receipt = new Receipt();
            $receipt->ee = str_replace(',', '.',  $this->ee);;
            $receipt->penalty = str_replace(',', '.',  $this->penalty);
            $receipt->contract = $this->contract;
            $receipt->user_id=\Yii::$app->user->id;
            $receipt->status = 'new';
            if ($receipt->save()) {
                return $receipt;
            } else {
                return false;
            }

        }
        return false;
    }

    public function noEmptyPay ($attr){
        $ee = (empty($this->ee))?0:$this->ee;
        $penalty = (empty($this->penalty))?0:$this->penalty;
        if ($ee + $penalty == 0){
            $this->addError($this->$attr, 'Нельзя оплачивать 0 РУБ.!');
        }
    }

}