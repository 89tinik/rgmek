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
    public $restriction_notify_fn;
    public $restriction_notify_p;
    public $restriction_notify_e;
    public $files;
    public $contact_name;
    public $contact_phone;
    public $contact_email;

    public $filesUpload;
    public $contractPriceForecast;
    public $contractVolumeForecast;
    public $pricePerPiece;
    public $responsible_4device_contact_fn;
    public $responsible_4device_contact_p;
    public $responsible_4device_contact_e;
    public $responsible_4calculation_contact_fn;
    public $responsible_4calculation_contact_p;
    public $responsible_4calculation_contact_e;
    public $director_full_name;
    public $director_position;
    public $director_order;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['id', 'user_id', 'contract_id', 'contract_volume_plane_include', 'off_budget'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
            [['restriction_notify_e', 'contact_email', 'responsible_4device_contact_e', 'responsible_4calculation_contact_e'], 'email'],
            [['contact_phone', 'responsible_4device_contact_p', 'responsible_4calculation_contact_p'], 'match', 'pattern' => '/^\d{6,}$/', 'message' => 'Телефон должен содержать только цифры и минимум 6 цифр.'],
            [['contact_name', 'restriction_notify_fn', 'responsible_4calculation_contact_fn', 'responsible_4device_contact_fn'], 'match', 'pattern' => '/^[А-ЯЁа-яё\s-]{3,}$/u', 'message' => 'Поле должно содержать только русские буквы, пробелы или дефисы, и быть длиной не менее 3 символов.'],
            [['restriction_notify_fn', 'restriction_notify_e', 'contact_email', 'responsible_4device_contact_e', 'responsible_4calculation_contact_e',
                'contact_name', 'contact_phone', 'responsible_4device_contact_fn', 'responsible_4device_contact_p',
                'responsible_4calculation_contact_fn', 'responsible_4calculation_contact_p', 'director_full_name',
                'ikz','director_position', 'director_order', 'restriction_notify_p'
            ], 'required'],
            ['restriction_notify_p', 'match', 'pattern' => '/^\+7\d{10}$/', 'message' => 'Номер телефона должен быть в формате +71111111111.'],
            [['contract_price', 'off_budget_value', 'budget_value', 'contract_volume_plane'], 'string'],
            [['files'], 'string'],
            [['contract_type', 'basis_purchase', 'ikz', 'source_funding', 'off_budget_name', 'restriction_notify_p'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['filesUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx', 'maxFiles' => 10],
            [['director_order'], 'checkDirectorOrder'],
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
            'ikz' => 'Идентификационный код закупки (ИКЗ)*',
            'contract_price' => 'Цена контракта (договора), руб.',
            'contract_volume_plane' => 'Планируемый объем контракта(договора), кВтч',
            'contract_volume_plane_include' => 'Включать планируемый объем в контракт',
            'source_funding' => 'Источник финансирования',
            'off_budget' => 'Используются внебюджетные средства',
            'off_budget_name' => 'Иной источник финансирования',
            'off_budget_value' => 'Денежные средства из иного источника, руб',
            'budget_value' => 'Денежные средства из бюджета, руб',
            'restriction_notify_fn' => 'Контакты для получения уведомлений о введении ограничения*',
            'restriction_notify_p' => 'Мобильный телефон*',
            'restriction_notify_e' => 'E-mail',
            'files' => 'Файлы',
            'contact_name' => 'Контактное лицо по заявлению*',
            'contact_phone' => 'Телефон*',
            'contact_email' => 'E-mail*',
            'contractPriceForecast' => 'Прогнозируемая цена контракта (договора), руб.',
            'contractVolumeForecast' => 'Прогнозируемый объем контракта (договора), кВтч  ',
            'pricePerPiece' => 'Цена за 1 кВтч с НДС, руб.',
            'responsible_4device_contact_fn' => 'Лицо, ответственное за приборы учета и показания*',
            'responsible_4device_contact_p' => 'Телефон*',
            'responsible_4device_contact_e' => 'E-mail*',
            'responsible_4calculation_contact_fn' => 'Лицо, ответственное за взаиморасчеты*',
            'responsible_4calculation_contact_p' => 'Телефон*',
            'responsible_4calculation_contact_e' => 'E-mail*',
            'director_full_name' => 'ФИО руководителя (подписанта)*',
            'director_position' => 'Должность руководителя (подписанта)*',
            'director_order' => 'Действует на основании*',
            'filesUpload' => '',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function checkDirectorOrder($attribute, $params)
    {
        $draft = DraftContract::findOne(['user_id' => \Yii::$app->user->id, 'contract_id' => $this->contract_id]);
        $tempDataArr = json_decode($draft->temp_data, true);
        $filesArr = json_decode($draft->files, true);
        $emptyFiles = true;
        foreach ($filesArr as $fileArr) {
            $currFile = array_shift($fileArr);
            if(!empty($currFile)) {
                $emptyFiles = false;
                continue;
            }
        }
        if ($this->director_order != $tempDataArr['DirectorOrder'] && $emptyFiles) {
            $this->addError($attribute, 'Прикрепите документ подтверждающий основания');
        }
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

    public function strToNum()
    {
        $numAttrArray = ['contract_price', 'budget_value', 'off_budget_value'];
        foreach ($numAttrArray as $attr) {
            $this->$attr = str_replace(' ', '', $this->$attr);
        }

    }
}
