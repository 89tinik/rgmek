<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 */
class Messages extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN_UPDATE = 'adminUpdate';
    const SCENARIO_USER_UPDATE = 'userUpdate';

    public $answerFilesUpload;
    public $filesUpload;
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
            [['subject_id', 'contract_id', 'message', 'user_id', 'status_id'], 'required'],
            [['subject_id', 'contract_id', 'user_id', 'status_id', 'new'], 'integer'],
            [['message', 'files', 'answer', 'answer_files'], 'string'],
            [['created', 'published'], 'safe'],
            [['admin_num'], 'string', 'max' => 255],
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
            'subject' => 'Тема',
            'contract_id' => 'Договор',
            'message' => 'Сообщение',
            'files' => 'Файлы',
            'created' => 'Создано',
            'user_id' => 'Пользователь',
            'status_id' => 'Статус',
            'published' => 'Дата публикации',
            'admin_num' => 'Присвоенный номер',
            'new' => 'Новое сообщение',
            'answer' => 'Ответ',
            'answer_files' => 'Файлы ответа',
            'answerFilesUpload' => 'Загрузить файлы ответа',
            'filesUpload' => 'Загрузить файлы',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Преобразуем формат даты перед сохранением в базу данных
            if ($this->published) {
                $this->published = \Yii::$app->formatter->asDate($this->published, 'yyyy-MM-dd');
            }
            return true;
        }
        return false;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_ADMIN_UPDATE] = ['new', 'status_id'];
        $onetimeChange = ['answer_files', 'published', 'admin_num', 'answer'];
        foreach ($onetimeChange as $property) {
            if(empty($this->$property)){
                $scenarios[self::SCENARIO_ADMIN_UPDATE][]= $property;
            }
        }


        $scenarios[self::SCENARIO_USER_UPDATE] = ['files'];

        return $scenarios;
    }
    public function uploadFiles($id)
    {
        if ($this->validate() && empty($this->answer_files)) {
            $paths = [];
            foreach ($this->answerFilesUpload as $file) {
                $filename = str_replace(' ', '-', $file->baseName) . '.' . $file->extension;
                $filePath = 'uploads/tickets/' . $id . '/' . $filename;
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
}
