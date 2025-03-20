<?php

namespace app\models;

use Mpdf\Mpdf;
use Yii;

/**
 * This is the model class for table "draft_contract".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $contract_id
 * @property string|null $contract_type
 * @property string|null $from_date
 * @property string|null $to_date
 * @property string|null $basis_purchase
 * @property string|null $ikz
 * @property float|null $contract_price
 * @property float|null $contract_volume_plane
 * @property float|null $contract_volume_plane_include
 * @property string|null $source_funding
 * @property int|null $off_budget
 * @property string|null $off_budget_name
 * @property float|null $off_budget_value
 * @property float|null $budget_value
 * @property string|null $restriction_notify_p
 * @property string|null $restriction_notify_e
 * @property string|null $files
 * @property string|null $contact_name
 * @property string|null $contact_phone
 * @property string|null $contact_email
 *
 * @property User $user
 */
class DraftContract extends BaseDraft
{
    const UPLOAD_FILES_FOLDER_PATH = 'uploads/draft-contracts/';
    const MESSAGE_THEME = 6;
    const MESSAGE_TEXT = 'Направлено заявление на заключение контракта (договора) энергоснабжения на следующий период';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'contract_volume_plane_include', 'off_budget', 'contract_id', 'last'], 'integer'],
            [['from_date', 'to_date', 'send'], 'safe'],
            [['contract_price', 'contract_volume_plane', 'off_budget_value', 'budget_value'], 'number'],
            [['files', 'temp_data'], 'string'],
            [['contract_type', 'basis_purchase', 'ikz', 'source_funding', 'off_budget_name', 'restriction_notify_fn',
                'restriction_notify_p', 'restriction_notify_e', 'contact_name', 'contact_phone', 'contact_email',
                'responsible_4device_contact_fn', 'responsible_4device_contact_p', 'responsible_4device_contact_e',
                'responsible_4calculation_contact_fn', 'responsible_4calculation_contact_p', 'responsible_4calculation_contact_e',
                'director_full_name', 'director_position', 'director_order'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
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
            'restriction_notify_fn' => 'Контакты для получения уведомлений о введении ограничения',
            'restriction_notify_p' => 'Телефон',
            'restriction_notify_e' => 'E-mail',
            'files' => 'Файлы',
            'contact_name' => 'Контактное лицо по заявлению',
            'contact_phone' => 'Телефон',
            'contact_email' => 'E-mail',
            'send' => 'Отправлено',
            'last' => 'Последний редактируемый',
            'responsible_4device_contact_fn' => 'Лицо, ответственное за приборы учета и показания',
            'responsible_4device_contact_p' => 'Телефон',
            'responsible_4device_contact_e' => 'E-mail',
            'responsible_4calculation_contact_fn' => 'Лицо, ответственное за взаиморасчеты',
            'responsible_4calculation_contact_p' => 'Телефон',
            'responsible_4calculation_contact_e' => 'E-mail',
            'director_full_name' => 'ФИО руководителя (подписанта)',
            'director_position' => 'Должность руководителя (подписанта)',
            'director_order' => 'Действует на основании',
            'temp_data' => 'Не редактируемые данные'
        ];
    }


    public function fileToMessage($folderId)
    {
        $uploadDirectory = 'uploads/tickets/' . $folderId;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $fileArr = json_decode($this->files, true);
        $paths = [];
        if (is_array($fileArr)) {
            foreach ($fileArr as $file) {
                $path = reset($file);
                if (!empty($path)) {
                    $newPath = $uploadDirectory . '/' . basename($path);
                    if (copy($path, $newPath)) {
                        $paths[] = $newPath;
                    }
                }
            }
        }
        $fileName = date('d.m.Y H:i') . '_Заявление_'.$this->contract_id.'.pdf';
        $this->generatePdf($fileName);
        $filePath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        $newPath = $uploadDirectory . '/' . basename($filePath);
        if (rename($filePath, $newPath)) {
            $paths[] = $newPath;
        }
        return json_encode($paths);
    }

    public function generatePdf($fileName = 'Обращение.pdf')
    {
        $mpdf = new Mpdf([
            'tempDir' => 'tmp-mpdf'
        ]);

        $html = '';
        $contactHtml = '';
        $fileHtml = '';
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'user_id':
                    $value = User::findOne($value)->full_name;
                    break;
                case 'temp_data':
                case 'last':
                case 'send':
                    continue 2;
                case 'contract_volume_plane_include':
                    $value = ($value == 1) ? $this->contract_volume_plane : 'Нет';
                    break;
                case 'off_budget':
                    if ($value == 1) {
                        $value = 'Да';
                    } else {
                        $value = 'Нет';
                        $off_budget = 1;
                    }
                    break;
                case 'off_budget_name':
                    if ($this->off_budget != 1) {
                        continue 2;
                    }
                    break;
                case 'files':
                    $fileArr = json_decode($value, true);
                    $tempFiles = [];
                    if (is_array($fileArr)) {
                        foreach ($fileArr as $file) {
                            $tempFiles[] = reset($file);
                        }
                        $value = implode(';', $tempFiles);
                    } else {
                        $value = '';
                    }
                    $fileHtml .= '<p><b>' . $this->getAttributeLabel($attribute) . ':</b>' . $value . '</p>';
                    continue 2;
                case 'contact_name':
                case 'contact_phone':
                case 'contact_email':
                    $contactHtml .= '<p><b>' . $this->getAttributeLabel($attribute) . ':</b>' . $value . '</p>';
                    continue 2;
            }
            if ($off_budget == 1 && in_array($attribute, ['off_budget_value', 'budget_value']) || $attribute == 'contract_volume_plane') continue;
            $html .= '<p><b>' . $this->getAttributeLabel($attribute) . ':</b> ' . $value . '</p>';
        }


        $mpdf->WriteHTML($html . $contactHtml . $fileHtml . '<p align="right">'.date('d.m.Y').'</p>');

        $pdfPath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
    }

    /**
     * @param $defaultArr
     * @return void
     */
    public function setDefault($defaultArr)
    {
        $nullAttributes = array_keys($this->getNullAttr());
        $ArrayModelAttributesto1C = $this->getArrayModelAttributesto1C();
        foreach ($nullAttributes as $attribute) {
            switch ($attribute) {
                case 'contract_volume_plane_include':
                case 'off_budget':
                    $this->$attribute = ($defaultArr[$ArrayModelAttributesto1C[$attribute]]) ? 1 : 0;
                    break;
                case 'contract_type':
                    $index = array_search($defaultArr['ContractType'], array_column($defaultArr['ContractTypeList']['item'], 'id'));
                    $this->$attribute = $defaultArr['ContractTypeList']['item'][$index]['description'];
                    break;
                case 'basis_purchase':
                    $index = array_search($defaultArr['Basis'], array_column($defaultArr['BasisList']['item'], 'id'));
                    $this->$attribute = $defaultArr['BasisList']['item'][$index]['description'];
                    break;
                case 'source_funding':
                    $index = array_search($defaultArr['FundingSource'], array_column($defaultArr['FundingSourceList']['item'], 'id'));
                    $this->$attribute = $defaultArr['FundingSourceList']['item'][$index]['description'];
                    break;
                case 'contract_id':
                    $index = array_search($defaultArr['ContractNumber'], array_column($defaultArr['ContractNumberList']['item'], 'id'));
                    $this->$attribute = ($defaultArr['ContractNumberList']['item']['description']) ?? $defaultArr['ContractNumberList']['item'][$index]['description'];
                    break;
                case 'temp_data':
                    $keys = array_flip([
                        'ContractNumberList',
                        'ContractTypeList',
                        'BasisList',
                        'FundingSourceList',
                        'ContractPriceForecast',
                        'ContractVolumeForecast',
                        'PricePerPiece',
                        'DirectorOrder',
                        'DirectorPosition',
                        'DirectorGender'
                    ]);
                    $this->$attribute = json_encode(array_intersect_key($defaultArr, $keys));
                    break;
                case 'restriction_notify_p':
                case 'responsible_4device_contact_p':
                case 'responsible_4calculation_contact_p':
                    if (is_array($ArrayModelAttributesto1C[$attribute])) {
                        $value = $defaultArr[$ArrayModelAttributesto1C[$attribute][0]][$ArrayModelAttributesto1C[$attribute][1]];
                    } else {
                        $value = $defaultArr[$ArrayModelAttributesto1C[$attribute]];
                    }
                    $this->$attribute = (!empty($value)) ? preg_replace('/[^0-9]/', '', $value) : NULL;
                    break;
                default:
                    if (is_array($ArrayModelAttributesto1C[$attribute])) {
                        $value = $defaultArr[$ArrayModelAttributesto1C[$attribute][0]][$ArrayModelAttributesto1C[$attribute][1]];
                    } else {
                        $value = $defaultArr[$ArrayModelAttributesto1C[$attribute]];
                    }
                    $this->$attribute = (!empty($value)) ? $value : NULL;

            }
        }
    }

    /**
     * @return array
     */
    public function getArrayModelAttributesto1C()
    {
        return [
            'contract_id' => 'ContractNumber',
            'contract_type' => 'ContractType',
            'from_date' => 'WithDate',
            'to_date' => 'ByDate',
            'basis_purchase' => 'Basis',
            'ikz' => 'PurchaseIdentificationCode',
            'contract_price' => 'ContractPrice',
            'contract_volume_plane' => 'ContractVolume',
            'contract_volume_plane_include' => 'IncludeVolumeInContract',
            'source_funding' => 'FundingSource',
            'off_budget' => 'ExtraBudgetaryFundsEnable',
            'off_budget_name' => 'FundingSourceAnother',
            'off_budget_value' => 'ContractPriceAnother',
            'budget_value' => 'BudgetFunds',
            'restriction_notify_fn' => ['RestrictionNotifyContact', 'FullName'],
            'restriction_notify_p' => ['RestrictionNotifyContact', 'Phone'],
            'restriction_notify_e' => ['RestrictionNotifyContact', 'Email'],
            'contact_name' => ['ContactPerson4Request', 'FullName'],
            'contact_phone' => ['ContactPerson4Request', 'Phone'],
            'contact_email' => ['ContactPerson4Request', 'Email'],
            'responsible_4device_contact_fn' => ['Responsible4DeviceContact', 'FullName'],
            'responsible_4device_contact_p' => ['Responsible4DeviceContact', 'Phone'],
            'responsible_4device_contact_e' => ['Responsible4DeviceContact', 'Email'],
            'responsible_4calculation_contact_fn' => ['Responsible4СalculationContact', 'FullName'],
            'responsible_4calculation_contact_p' => ['Responsible4СalculationContact', 'Phone'],
            'responsible_4calculation_contact_e' => ['Responsible4СalculationContact', 'Email'],
            'director_full_name' => 'DirectorFullName',
            'director_position' => 'DirectorPosition',
            'director_order' => 'DirectorOrder'
        ];
    }
}

