<?php


namespace app\models;


use yii\base\Model;

class AttachForm extends Model
{
    public $puid;
    public $photo;
    public $time;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['photo'], 'file', 'extensions' => 'png, jpg'],
            [['time'], 'file', 'extensions' => 'xls, xlsx'],
            ['puid', 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'puid' => 'ID счетчика',
            'photo' => 'Фото счетчика',
            'time' => 'Почасовые объемы'
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string|array $email the target email address
     * @return bool whether the model passes validation
     */
    public function sendMail($email, $photo, $time)
    {
        $result = \Yii::$app->mailer->compose([
            'html' => 'attach-html'], [
            'fields' => ['puid' => $this->puid]
        ]);
        if ($photo->tempName) {
            $content_photo = file_get_contents($photo->tempName);
            $result->attachContent($content_photo, [
                'fileName' => $photo->baseName . '.' . $photo->extension,
                'contentType' => $photo->type]);
        }
        if ($time->tempName) {
            $content_time = file_get_contents($time->tempName);
            $result->attachContent($content_time, [
                'fileName' => $time->baseName . '.' . $time->extension,
                'contentType' => $time->type]);
        }
        $result->setTo($email);
        $result->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']]);
        $result->setSubject('Подтверждающие файлы');
        if ($result->send()) {
            return true;
        } else {
            return false;
        }

    }



}