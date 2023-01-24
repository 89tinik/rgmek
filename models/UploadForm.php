<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $path;
    public $link;
    public $disable;
    public $id;
    public $sort;

    public function rules()
    {
        return [
            [['path'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
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
            'path' => 'Банер(400x220)',
            'link' => 'Ссылка(не обязательно)',
            'disable' => 'Отключен',
            'sort' => 'Сортировка',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $banerPath = 'uploads/' . $this->path->baseName . '.' . $this->path->extension;
            $this->path->saveAs($banerPath);
            $baner = new Baner();
            $baner->path = $banerPath;
            $baner->sort = $this->sort;
            $baner->link = $this->link;
            $baner->disable = $this->disable;
            if ($baner->save()) {
                return true;
            }
        }
        return false;
    }
}