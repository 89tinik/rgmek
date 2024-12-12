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
 * @property string|null $user_phone
 * @property string|null $user_email
 * @property string|null $files
 * @property string|null $contact_name
 * @property string|null $contact_phone
 * @property string|null $contact_email
 *
 * @property User $user
 */
class DraftContract extends \yii\db\ActiveRecord
{
    const UPLOAD_FILES_FOLDER_PATH = 'uploads/draft-contracts/';
    const TITLE = 'Направить заявление на заключение контракта (договора) энергоснабжения на следующий период';

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
            [['user_id', 'contract_id', 'contract_volume_plane_include', 'off_budget'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
            [['contract_price', 'contract_volume_plane', 'off_budget_value', 'budget_value'], 'number'],
            [['files'], 'string'],
            [['contract_type', 'basis_purchase', 'ikz', 'source_funding', 'off_budget_name', 'user_phone', 'user_email', 'contact_name', 'contact_phone', 'contact_email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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

    /**
     * @param $idx int
     * @return true|void
     */
    public function removeFile($idx)
    {
        $filesArr = json_decode($this->files, true);
        foreach ($filesArr as &$file) {
            if (array_key_first($file) == $idx) {
                unlink($file[$idx]);
                $file[$idx] = '';
            }
        }
        $filesJson = json_encode($filesArr);
        $this->files = $filesJson;
        if ($this->save()) {
            return true;
        }
    }

    public function fileToMessage($folderId)
    {
        $uploadDirectory = 'uploads/tickets/' . $folderId;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $fileArr = json_decode($this->files, true);
        $paths = [];
        foreach ($fileArr as $file) {
            $path = reset($file);
            if (!empty($path)) {
                $newPath = $uploadDirectory . '/' . basename($path);
                if (rename($path, $newPath)) {
                    $paths[] = $newPath;
                }
            }
        }
        return json_encode($paths);
    }

    public function generatePdf($fileName = 'Обращение.pdf')
    {
        $mpdf = new Mpdf([
            'tempDir' => 'tmp-mpdf'
        ]);
        $filesUploadNames = '';
        if (!empty($this->files)) {
            $filesUploadNames = implode(', ', json_decode($this->files, true));
        }
        if (!empty($filesUploadNames) && !empty($this->filesUploadNames)){
            $filesUploadNames .= ', ';
        }
        $filesUploadNames .= $this->filesUploadNames;
        $html = '';
        foreach ($this->attributes as $attribute => $value) {
            $html .= '<p><b>'.$this->getAttributeLabel($attribute).':</b>'.$value.'</p>';
        }


        $mpdf->WriteHTML($html);

        $pdfPath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
    }
}
