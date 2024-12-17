<?php

namespace app\models;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Yii;
use yii\httpclient\Client;

class Messages extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';
    const SCENARIO_USER_UPDATE = 'userUpdate';

    public $answerFilesUpload;

    public $filesUpload;
    public $filesUploadNames;

    public $message_count;
    public $user_name;
    public $user_email;
    public $user_phone;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject_id', 'message', 'user_id', 'status_id'], 'required', 'on' => [self::SCENARIO_ADMIN_UPDATE, self::SCENARIO_USER_UPDATE]],
            [['admin_num', 'published'], 'required', 'on' => self::SCENARIO_ADMIN_UPDATE],
            [['subject_id', 'message', 'user_id', 'email'], 'required', 'on' => self::SCENARIO_CREATE],
            [['contract_id'], 'required', 'on' => [self::SCENARIO_ADMIN_UPDATE, self::SCENARIO_USER_UPDATE], 'message' => 'Пожалуйста, выберите номер договора'],
            [['contract_id'], 'required', 'on' => self::SCENARIO_CREATE, 'message' => 'Пожалуйста, выберите номер договора'],
            [['subject_id', 'contract_id', 'user_id', 'status_id', 'new'], 'integer'],
            [['email'], 'email'],
            [['message', 'files', 'answer', 'answer_files', 'filesUploadNames'], 'string'],
            [['created', 'published', 'update'], 'safe'],
            [['admin_num', 'contact_name', 'phone'], 'string', 'max' => 255],
            [['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contract::class, 'targetAttribute' => ['contract_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => MessageStatuses::class, 'targetAttribute' => ['status_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Theme::class, 'targetAttribute' => ['subject_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['answerFilesUpload', 'filesUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx', 'maxFiles' => 10],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Тема',
            'contract_id' => 'Договор',
            'message' => 'Текст обращения',
            'files' => 'Файлы',
            'created' => 'Создано',
            'user_id' => 'Потребитель',
            'status_id' => 'Статус',
            'published' => 'Дата публикации',
            'admin_num' => 'Присвоенный номер',
            'new' => 'Новое сообщение',
            'answer' => 'Ответ',
            'answer_files' => 'Файлы ответа',
            'answerFilesUpload' => 'Загрузить файлы ответа',
            'filesUpload' => 'Загрузить файлы',
            'email' => 'E-mail контактного лица по обращению',
            'contact_name' => 'Контактное лицо по обращению',
            'update' => 'Обновлено',
            'phone' => 'Телефон контактного лица по обращению',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->published) {
                $this->published = \Yii::$app->formatter->asDate(new \DateTime($this->published), 'yyyy-MM-dd');
            }
            return true;
        }
        return false;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['subject_id', 'contract_id', 'message', 'user_id', 'files', 'email', 'phone', 'contact_name'];

        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['new', 'status_id', 'update'];
        $onetimeChange = ['answer_files', 'published', 'admin_num', 'answer'];
        foreach ($onetimeChange as $property) {
            if (empty($this->$property)) {
                $scenarios[self::SCENARIO_ADMIN_UPDATE][] = $property;
            }
        }

        $scenarios[self::SCENARIO_USER_UPDATE] = ['files', 'new', 'update'];

        return $scenarios;
    }

    public function uploadFiles($id, $files = 'filesUpload')
    {
        if ($this->validate() && empty($this->answer_files)) {
            $paths = [];
            foreach ($this->$files as $file) {
                $filename = str_replace(' ', '-', $file->baseName) . '.' . $file->extension;
                $filePath = 'uploads/tickets/' . $id . '/' . $filename;
                if (is_file($filePath)) {
                    $filePath = 'uploads/tickets/' . $id . '/(' . time() . ')' . $filename;
                }
                if ($file->saveAs($filePath)) {
                    $paths[] = $filePath;
                }
            }
            return $paths;
        }
        return false;
    }

    /**
     * Gets query for [[Contract]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContract()
    {
        return $this->hasOne(Contract::class, ['id' => 'contract_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(MessageStatuses::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(MessageThemes::class, ['id' => 'subject_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getMessageHistory()
    {
        return $this->hasMany(MessageHistory::class, ['message_id' => 'id']);
    }

    /**
     * @param $text string
     * @return string[]|true
     */
    public function sendNoticeSms(string $text, string $phone)
    {
        //отправляем SMS
        $client = new Client();
        $phone = substr_replace($phone, '7', 0, 1);
        $username = '1a4a5f50fc';
        $password = 'e599316079';
        $data = [
            'msisdn' => $phone,
            'shortcode' => 'rgmek',
            'text' => $text
        ];

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setHeaders(['Authorization' => 'Basic ' . base64_encode("$username:$password")])
            ->setUrl('https://target.t2.ru/api/v2/send_message')
            ->setData($data)
            ->send();

        if (!$response->isOk) {
            return ['error' => 'Не удалось отправить SMS! Статус:'.$response->statusCode];
        } else {
            $responseArrContent = json_decode($response->content, true);
            if ($responseArrContent['status'] == 'error') {
                return ['error' => 'Не удалось отправить SMS! Error:' . $responseArrContent['reason']];
            }
        }
        return true;
    }

    /**
     * @param $subject string
     * @param $text string
     * @return string[]|true
     */
    public function sendNoticeEmail(string $subject, string $text, string $email)
    {
        //отправляем почту
        $mail = Yii::$app->mailer->compose()
            ->setFrom('noreply@send.rgmek.ru')
            ->setTo($email)
            ->setSubject($subject)
            ->setHtmlBody($text)
            ->send();
        if (!$mail) {
            return ['error' => 'Не удалось отправить письмо.'];
        }
        return true;
    }

    /**
     * @param $subject string
     * @return false|string
     * @throws MpdfException
     */
    public function sendAdminNoticeEmail(string $subject='Новое обращение')
    {
        $fileName = date('d.m.Y H:i') . '_' . $this->contract->number . '.pdf';
        $this->generatePdf($fileName);
        $filePath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        //отправляем почту
        $mail = Yii::$app->mailer->compose()
            ->setFrom('noreply@send.rgmek.ru')
            ->setTo(['lk@rgmek.ru'])
            ->setSubject($subject)
            ->setHtmlBody('Детали во вложении')
            ->attach($filePath);
        if ($this->files){
            $attaches = json_decode($this->files, true);
            foreach ($attaches as $attach) {
                $mail->attach($attach);
            }
        }
        $mail->send();
        if (!$mail) {
            return false;
        }
        return $filePath;
    }

    /**
     * @throws MpdfException
     */
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
        $html = Yii::$app->view->render('@app/views/new-message/pdf', [
            'date' => date('d.m.Y H:i'),
            'user' => $this->user->full_name,
            'contract' => ($this->contract ? $this->contract->number : 'Не указан'),
            'subject' => ($this->subject ? $this->subject->title : 'Не указана'),
            'message' => $this->message,
            'filesUploadNames' => $filesUploadNames,
            'contactName' => $this->contact_name,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);


        $mpdf->WriteHTML($html);

        $pdfPath = Yii::getAlias('@webroot') . '/temp_pdf/' . $fileName;
        $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
    }

    /**
     * @param DraftContract $draft
     * @return int
     */
    public static function createMessageFromDraft($draft)
    {
        $message = new self();
        $message->scenario = self::SCENARIO_CREATE;
        $message->status_id = MessageStatuses::RECD;
        $message->subject_id = 5;
        $message->contract_id = Contract::findOne(['number'=>$draft->contract_id])->id;
        $message->message = 'Направлено заявление на заключение контракта (договора) энергоснабжения на следующий период';
        $message->user_id = $draft->user_id;
        $message->email = $draft->contact_email;
        $message->phone = $draft->contact_phone;
        $message->contact_name = $draft->contact_name;
        if ($message->save()){
            MessageHistory::setNewMessage($message->id);

            $message->files = $draft->fileToMessage($message->id);
            $message->save();
            return $message->id;
        }
    }
}
