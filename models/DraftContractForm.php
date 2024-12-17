<?php

namespace app\models;

use yii\base\Model;

class DraftContractForm extends Model
{
    public $id;
    public $contract_id;
    public $user_id;
    public $contract_type;
    public $from_date;
    public $to_date;
    public $basis_purchase;
    public $ikz;
    public $contract_price;
    public $contract_volume_plane;
    public $contract_volume_plane_include;
    public $source_funding;
    public $off_budget;
    public $off_budget_name;
    public $off_budget_value;
    public $budget_value;
    public $user_phone;
    public $user_email;
    public $files;
    public $contact_name;
    public $contact_phone;
    public $contact_email;

    public $filesUpload;
    public $contractPriceForecast;
    public $contractVolumeForecast;
    public $pricePerPiece;
    public $responsible4DeviceContactFN;
    public $responsible4DeviceContactP;
    public $responsible4DeviceContactE;
    public $responsible4СalculationContactFN;
    public $responsible4СalculationContactP;
    public $responsible4СalculationContactE;
    public $directorFullName;
    public $directorPosition;
    public $directorOrder;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['id', 'user_id', 'contract_id', 'contract_volume_plane_include', 'off_budget'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
            [['contract_volume_plane'], 'number'],
            [['contract_price', 'off_budget_value', 'budget_value'], 'string'],
            [['files'], 'string'],
            [['contract_type', 'basis_purchase', 'ikz', 'source_funding', 'off_budget_name', 'user_phone', 'user_email', 'contact_name', 'contact_phone', 'contact_email'], 'string', 'max' => 255],
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
            'contract_type' => 'Вид контракта (договора)',
            'from_date' => 'С',
            'to_date' => 'По',
            'basis_purchase' => 'Основание закупки',
            'ikz' => 'Идентификационный код закупки (ИКЗ)',
            'contract_price' => 'Цена контракта (договора), руб.',
            'contract_volume_plane' => 'Планируемый объем контракта(договора), кВтч',
            'contract_volume_plane_include' => 'Включать планируемый объем в контракт',
            'source_funding' => 'Источник финансирования',
            'off_budget' => 'Используются внебюджетные средства',
            'off_budget_name' => 'Иной источник финансирования',
            'off_budget_value' => 'Денежные средства из иного источника, руб',
            'budget_value' => 'Денежные средства из бюджета, руб',
            'user_phone' => 'Телефон',
            'user_email' => 'E-mail',
            'files' => 'Файлы',
            'contact_name' => 'Контактное лицо по заявлению',
            'contact_phone' => 'Телефон',
            'contact_email' => 'E-mail',
            'contractPriceForecast' => 'Прогнозируемая цена контракта (договора), руб.',
            'contractVolumeForecast' => 'Прогнозируемый объем контракта (договора), кВтч  ',
            'pricePerPiece' => 'Цена за 1 кВтч с НДС, руб.',
            'responsible4DeviceContactFN' => 'Лицо, ответственное за приборы учета и показания',
            'responsible4DeviceContactP' => 'Телефон',
            'responsible4DeviceContactE' => 'E-mail',
            'responsible4СalculationContactFN' => 'Лицо, ответственное за взаиморасчеты',
            'responsible4СalculationContactP' => 'Телефон',
            'responsible4СalculationContactE' => 'E-mail',
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
            $filePath = DraftContract::UPLOAD_FILES_FOLDER_PATH . $id . '/' . $filename;
            if (is_file($filePath)) {
                $filePath = DraftContract::UPLOAD_FILES_FOLDER_PATH . $id . '/(' . time() . ')' . $filename;
            }
            if ($file->saveAs($filePath)) {
                $paths[] = ['idx' . $idx => $filePath];
            }
        }
        return $paths;
    }

}
