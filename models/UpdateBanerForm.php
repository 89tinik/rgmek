<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UpdateBanerForm extends Model
{
    public $link;
    public $disable;
    public $id;
    public $sort;

    public function rules()
    {
        return [
            [['disable'], 'integer'],
            [['id'], 'integer'],
            [['sort'], 'integer'],
            [['link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Ссылка(не обязательно)',
            'disable' => 'Отключен',
            'sort' => 'Сортировка',
        ];
    }

    public function updateBaner()
    {
        $baner = Baner::findOne($this->id);
        $baner->link = $this->link;
        $baner->disable = $this->disable;
        $baner->sort = $this->sort;
        return $baner->save();
    }
}