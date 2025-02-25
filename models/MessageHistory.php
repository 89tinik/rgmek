<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_history".
 *
 * @property int $id
 * @property int $message_id
 * @property string|null $created
 * @property string|null $log
 *
 * @property Messages $message
 */
class MessageHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id'], 'required'],
            [['message_id'], 'integer'],
            [['created'], 'safe'],
            [['log'], 'string', 'max' => 255],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Messages::class, 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'created' => 'Created',
            'log' => 'Log',
        ];
    }

    /**
     * Gets query for [[Message]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Messages::className(), ['id' => 'message_id']);
    }

    /**
     * @param int $messageId
     * @return void
     */
    public static function setNewMessage($messageId)
    {
        $modelHistory = new self();
        $modelHistory->log = 'Получено обращение';
        $modelHistory->message_id = $messageId;
        $modelHistory->created = date('Y-m-d H:i:s');
        $modelHistory->save();
    }
}
