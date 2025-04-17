<?php

namespace app\models;

use Mpdf\Mpdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Yii;

/**
 * This is the model class for table "draft_termination".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $contract_id
 * @property float|null $contract_price
 * @property float|null $contract_volume_price
 * @property string|null $send
 *
 * @property User $user
 */
class DraftTermination extends BaseDraft
{
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
            [['temp_data'], 'string'],
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

    public function generateWord($fileName = 'Соглашение.pdf')
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection([
            'marginTop'    => 350,
            'marginBottom' => 350,
            'marginLeft'   => 400,
            'marginRight'  => 800,
        ]);
        $wordData = [];
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'user_id':
                    $value = User::findOne($value)->full_name;
                    break;
                case 'send':
                    continue 2;
                case 'contract_volume_price':
                    $wordData['price_in_word'] = self::num2str($value);
                    break;
                case 'temp_data':
                    $wordData = array_merge($wordData, json_decode($value, true));
                    $wordData['penalty_word'] = self::num2str($wordData['Penalty']);
                    $wordData['provided_services_cost_word'] = self::num2str($wordData['ProvidedServicesCost']);
                    continue 2;
            }
            $wordData[$attribute] = $value;
        }
        $content = \Yii::$app->controller->renderPartial('@app/views/draft-termination/_word', $wordData);

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $content);

        $filename = 'DraftContractChange_' . $this->id . '.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("php://output");

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
