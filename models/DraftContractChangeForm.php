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

    public $files;
    public $contact_name;
    public $contact_phone;
    public $contact_email;
    public $filesUpload;

    public $directorFullName;
    public $directorPosition;
    public $directorOrder;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['id', 'user_id', 'contract_volume_plane_include'], 'integer'],
            [['files', 'contract_price', 'contract_volume', 'contract_price_new', 'contract_id', 'contract_volume_new'], 'string'],
            [['contact_name', 'contact_phone', 'contact_email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['filesUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx', 'maxFiles' => 10],
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
            'files' => 'Файлы',
            'contact_name' => 'Контактное лицо по заявлению',
            'contact_phone' => 'Телефон',
            'contact_email' => 'E-mail',
            'directorFullName' => 'ФИО руководителя (подписанта)',
            'directorPosition' => 'Должность руководителя (подписанта)',
            'directorOrder' => 'Действует на основании',
            'filesUpload' => '',
        ];
    }


    public function uploadFiles($id, $idx)
    {
        $paths = [];
        foreach ($this->filesUpload as $file) {
            $idx++;
            $filename = str_replace(' ', '-', $file->baseName) . '.' . $file->extension;
            $filePath = DraftContractChange::UPLOAD_FILES_FOLDER_PATH . $id . '/' . $filename;
            if (is_file($filePath)) {
                $filePath = DraftContractChange::UPLOAD_FILES_FOLDER_PATH . $id . '/(' . time() . ')' . $filename;
            }
            if ($file->saveAs($filePath)) {
                $paths[] = ['idx' . $idx => $filePath];
            }
        }
        return $paths;
    }

}
