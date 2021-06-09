<?php


namespace app\models;


use yii\base\Model;
use Yii;

class FeedbackForm extends Model
{
    public $name;
    public $surname;
    public $patronymic;

    public $email;
    public $phone;

    public $contract;
    public $entity;
    public $subject;

    public $body;
    public $polit;
    public $file;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'subject', 'body', 'polit'], 'required', 'message' => 'Заполните поле'],
            // email has to be a valid email address
            ['email', 'email'],
            [['phone', 'surname', 'patronymic', 'contract', 'entity', 'file'], 'trim'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'subject' => 'Тема сообщения',
            'body' => 'Сообщение',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'contract' => 'Номер договора',
            'entity' => 'Наименование юридического лица',
            'file' => 'Прикрепить приложение'
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function send($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose([
                'html' => 'feedback-html'], [
                'fields' => ['name' => $this->name,
                    'body' => $this->body,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'surname' => $this->surname,
                    'patronymic' => $this->patronymic,
                    'contract' => $this->contract,
                    'entity' => $this->entity]
            ])
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setSubject($this->subject)
                ->send();

            return true;
        }
        return false;
    }

    public function sendMail($email, $files)
    {
//        Yii::$app->mailer->getView()->params['name'] = $params['name'];
//        Yii::$app->mailer->getView()->params['email'] = $params['email'];
//        Yii::$app->mailer->getView()->params['question'] = $params['question'];

        $result = Yii::$app->mailer->compose([
            'html' => 'feedback-html'], [
            'fields' => ['name' => $this->name,
                'body' => $this->body,
                'email' => $this->email,
                'phone' => $this->phone,
                'surname' => $this->surname,
                'patronymic' => $this->patronymic,
                'contract' => $this->contract,
                'entity' => $this->entity]
        ]);

        foreach ($files as $file) {
            $content_file = file_get_contents($file->tempName);
            $result->attachContent($content_file, [
                'fileName' => $file->baseName . '.' . $file->extension,
                'contentType' => $file->type]);
        }

        $result->setTo($email);
        $result->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']]);
        $result->setSubject($this->subject);
        if ($result->send()){
            return true;
        } else {
            return false;
        }

//        Yii::$app->mailer->getView()->params['name'] = null;
//        Yii::$app->mailer->getView()->params['email'] = null;
//        Yii::$app->mailer->getView()->params['question'] = null;

    }
}