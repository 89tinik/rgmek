<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "baner".
 *
 * @property int $id
 * @property string|null $path
 * @property string|null $link
 * @property int|null $disable
 */
class Baner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'baner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['disable', 'sort'], 'integer'],
            [['path', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Банер',
            'link' => 'Ссылка',
            'disable' => 'Отключен',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteFile()
    {
        if(file_exists($this->path)){
            unlink($this->path);
        }
        $this->delete();
    }
}
