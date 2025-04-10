<?php

namespace app\models;

use Mpdf\Mpdf;
use Yii;

/**
 * This is the model class for table "draft_termination".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $contract_id
 * @property float|null $contract_price
 * @property float|null $contract_volume_price
 * @property string|null $files
 * @property string|null $send
 *
 * @property User $user
 */
class DraftTermination extends BaseDraft
{
    const UPLOAD_FILES_FOLDER_PATH = 'uploads/draft-termination/';
    const MESSAGE_THEME = 8;
    const MESSAGE_TEXT = 'Направлено заявление на расторжение действующего контракта (договора) энергоснабжения';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_termination';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'last'], 'integer'],
            [['contract_price', 'contract_volume_price'], 'number'],
            [['files', 'temp_data'], 'string'],
            [['send'], 'safe'],
            [['contract_id', 'director_full_name', 'director_position', 'director_order'], 'string', 'max' => 255],
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
            'contract_volume_price' => 'Стоимость электроэнергии, поставленной по контракту (договору), руб',
            'files' => 'Файлы',
            'send' => 'Отправлено',
            'director_full_name' => 'ФИО руководителя (подписанта)',
            'director_position' => 'Должность руководителя (подписанта)',
            'director_order' => 'Действует на основании',
            'temp_data' => 'Не редактируемые данные'
        ];
    }

    public function generatePdf($fileName = 'Соглашение.pdf')
    {
        $mpdf = new Mpdf([
            'tempDir' => 'tmp-mpdf',
            'default_font' => 'arial'
        ]);

        $pdfData = [];
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'user_id':
                    $value = User::findOne($value)->full_name;
                    break;
                case 'send':
                    continue 2;
                case 'contract_volume_price':
                    $pdfData['price_in_word'] = self::num2str($value);
                    break;
                case 'temp_data':
                    $pdfData = array_merge($pdfData, json_decode($value, true));
                    $pdfData['penalty_word'] = self::num2str($pdfData['Penalty']);
                    $pdfData['provided_services_cost_word'] = self::num2str($pdfData['ProvidedServicesCost']);
                    continue 2;
            }
            $pdfData[$attribute] = $value;
        }
        $html = Yii::$app->view->render('@app/views/draft-termination/pdf', $pdfData);

        $mpdf->WriteHTML($html);

        $mpdf->Output($fileName, \Mpdf\Output\Destination::INLINE);
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
                        'ProvidedServicesCost',
                        'Penalty',
                        'DirectorOrder',
                        'DirectorPosition',
                        'DirectorGender'
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
            'contract_volume_price' => 'ProvidedServicesCost',
            'director_full_name' => 'DirectorFullName',
            'director_position' => 'DirectorPosition',
            'director_order' => 'DirectorOrder'
        ];
    }
}
