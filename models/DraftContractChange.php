<?php

namespace app\models;

use app\components\CaseHelper;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;

/**
 * This is the model class for table "draft_contract_change".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $contract_id
 * @property float|null $contract_price_new
 * @property float|null $contract_volume_new
 * @property int|null $contract_volume_plane_include
 * @property string|null $send
 *
 * @property User $user
 */
class DraftContractChange extends BaseDraft
{
    const MESSAGE_THEME = 7;
    const MESSAGE_TEXT = 'Направлено заявление на изменение цены контракта (договора) энергоснабжения';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_contract_change';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'contract_volume_plane_include', 'last'], 'integer'],
            [['contract_price', 'contract_volume', 'contract_price_new', 'contract_volume_new'], 'number'],
            [['contract_id', 'temp_data'], 'string'],
            [['send'], 'safe'],
            [['director_full_name', 'director_position', 'director_order'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'contract_id' => 'Контракт (договор)',
            'contract_price' => 'Цена контракта (договора), руб.',
            'contract_volume' => 'Объем контракта(договора), кВтч',
            'contract_price_new' => 'Новая цена контракта (договора), руб.',
            'contract_volume_new' => 'Новый объем контракта(договора), кВтч',
            'contract_volume_plane_include' => 'Включать планируемый объем в контракт',
            'send' => 'Отправлено',
            'director_full_name' => 'ФИО руководителя (подписанта)',
            'director_position' => 'Должность руководителя (подписанта)',
            'director_order' => 'Действует на основании',
            'temp_data' => 'Не редактируемые данные'
        ];
    }


    public function generateWord()
    {
        $wordData= [];
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'user_id':
                    $value = User::findOne($value)->full_name;
                    break;
                case 'send':
                    continue 2;
                case 'contract_price_new':
                    $wordData['price_in_word'] = self::num2str($value);
                    break;
                case 'temp_data':
                    $wordData = array_merge($wordData, json_decode($value, true));
                    continue 2;
            }
            $wordData[$attribute] = $value;
        }
        if ($wordData['DirectorPosition'] != $wordData['director_position']) {
            $wordData['DirectorPositionRP'] = CaseHelper::getCase($wordData['director_position'], 1);
        }
        if ($wordData['DirectorOrder'] != $wordData['director_order']) {
            $wordData['DirectorOrderRP'] = CaseHelper::getCase($wordData['director_order'], 1);
        }
        //$active = ($wordData['DirectorGender'] == 'Мужской') ? ', действующего' : ', действующей';
        if ($wordData['DirectorFullName'] != $wordData['director_full_name']) {
            $wordData['DirectorFullNameRP'] = $wordData['director_full_name'];
            //$active = ', действующего(ей)';
        }
        $active = ', действующего';
        $doc = ($wordData['contract_volume_plane_include'] == 1) ? 'soglashenie_pl.docx' : 'soglashenie.docx';
        $template = new TemplateProcessor(Yii::getAlias('@app/views/draft-contract-change/'.$doc));

        $template->setValue('contract_number', $wordData['contract_id']);
        $template->setValue('user_id', $wordData['user_id']);
        $template->setValue('director_position_rp', $wordData['DirectorPositionRP']);
        $template->setValue('director_full_name_rp', $wordData['DirectorFullNameRP']);
        $template->setValue('active', $active);
        $template->setValue('director_order_rp', $wordData['DirectorOrderRP']);
        $template->setValue('contract_price_new', number_format(intval($wordData['contract_price_new']), 0, ',', ' '));
        $template->setValue('price_in_word', $wordData['price_in_word']);
        $template->setValue('contract_volume_forecast', number_format(intval($wordData['contract_volume_new']), 0, ',', ' ') . ' кВт.ч.');
        $template->setValue('director_position_capitalize', CaseHelper::ucfirstCyrillic($wordData['director_position']));
        $template->setValue('director_initials', CaseHelper::getInitials($wordData['director_full_name']));

        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $template->saveAs($tempFile);

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Соглашение об изменении договора энергоснабжения.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($tempFile);

        unlink($tempFile);

        exit;
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
                    $this->$attribute = ($defaultArr[$ArrayModelAttributesto1C[$attribute]] == 'false') ? 0 : 1;
                    break;
                case 'contract_id':
                    $index = array_search($defaultArr['ContractNumber'], array_column($defaultArr['ContractNumberList']['item'], 'id'));
                    $this->$attribute = ($defaultArr['ContractNumberList']['item']['description']) ?? $defaultArr['ContractNumberList']['item'][$index]['description'];
                    break;
                case 'temp_data':
                    $keys = array_flip([
                        'ContractNumberList',
                        'DirectorFullName',
                        'DirectorFullNameRP',
                        'DirectorFullNameDP',
                        'DirectorPositionRP',
                        'DirectorPositionDP',
                        'DirectorOrderRP',
                        'DirectorOrder',
                        'DirectorPosition',
                        'DirectorGender',
                        'PricePerPiece',
                        'ContractVolumeForecast'
                    ]);
                    $this->$attribute = json_encode(array_intersect_key($defaultArr, $keys));
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
            'contract_price' => 'ContractPrice',
            'contract_volume' => 'ContractVolume',
            'contract_price_new' => 'ContractPriceNew',
            'contract_volume_new' => 'ContractVolumeNew',
            'contract_volume_plane_include' => 'IncludeVolumeInContract',
            'director_full_name' => 'DirectorFullName',
            'director_position' => 'DirectorPosition',
            'director_order' => 'DirectorOrder'
        ];
    }
}
