<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $order_id
 * @property float $sum
 * @property string $status
 * @property string $created_at
 * @property string|null $pay_time
 * @property string $method
 * @property string|null $orderId
 * @property int|null $remote_id
 * @property string|null $data
 * @property string|null $url
 */
class Invoice extends \pantera\yii2\pay\sberbank\models\Invoice
{
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'sum' => 'Сумма оплаты',
            'status' => 'Статус оплаты',
            'created_at' => 'Created At',
            'pay_time' => 'Дата оплаты',
            'method' => 'Method',
            'orderId' => 'Order ID',
            'remote_id' => 'Remote ID',
            'data' => 'Data',
            'url' => 'Url',
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    public function getReceipt()
    {
        return $this->hasOne(Receipt::class, ['id' => 'order_id']);
    }
}
