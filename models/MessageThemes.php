<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_themes".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $content
 *
 * @property Messages[] $messages
 */
class MessageThemes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_themes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['hidden', 'id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тема',
            'content' => 'Описание',
            'hidden' => 'Скрытая',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['subject_id' => 'id']);
    }
}
