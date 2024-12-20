<?php

namespace app\models;

use Mpdf\Mpdf;
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
 * @property string|null $files
 * @property string|null $contact_name
 * @property string|null $contact_phone
 * @property string|null $contact_email
 * @property string|null $send
 *
 * @property User $user
 */
class DraftContractChange extends \yii\db\ActiveRecord
{
    const UPLOAD_FILES_FOLDER_PATH = 'uploads/draft-contracts-change/';
    const TITLE = 'Направить заявление на изменение цены контракта (договора) энергоснабжения';
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
            [['user_id', 'contract_volume_plane_include'], 'integer'],
            [['contract_price', 'contract_volume', 'contract_price_new', 'contract_volume_new'], 'number'],
            [['contract_id', 'files'], 'string'],
            [['send'], 'safe'],
            [['contact_name', 'contact_phone', 'contact_email'], 'string', 'max' => 255],
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
            'files' => 'Файлы',
            'contact_name' => 'Контактное лицо по заявлению',
            'contact_phone' => 'Телефон',
            'contact_email' => 'E-mail',
            'send' => 'Отправлено',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
        $fileName = date('d.m.Y H:i') . '_Заявление.pdf';
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
        foreach ($this->attributes as $attribute => $value) {
            switch ($attribute) {
                case 'user_id':
                    $value = User::findOne($value)->full_name;
                    break;
                case 'off_budget':
                case 'contract_volume_plane_include':
                    $value = ($value == 1) ? 'Да' : 'Нет';
                    break;
                case 'files':
                    $fileArr = json_decode($value, true);
                    $tempFiles = [];
                    if(is_array($fileArr)){
                        foreach ($fileArr as $file) {
                            $tempFiles[] = reset($file);
                        }
                        $value = implode(';', $tempFiles);
                    } else {
                        $value = '';
                    }
                    break;
            }
            $html .= '<p><b>' . $this->getAttributeLabel($attribute) . ':</b>' . $value . '</p>';
        }


        $mpdf->WriteHTML($html);

        $pdfPath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
    }
}
