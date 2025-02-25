<?php

namespace app\controllers;

use app\models\MessageHistory;
use app\models\Messages;
use app\models\MessageStatuses;
use Mpdf\Mpdf;
use Yii;
use app\models\MessageThemes;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MessagesController implements the CRUD actions for MessageThemes model.
 */
class NewMessageController extends Controller
{
    public $layout = 'inner';
    public $userName = '';
    public $listContract = '';
    public $piramida = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (isset(\Yii::$app->user->identity->full_name)) {
            $this->userName = \Yii::$app->user->identity->full_name;

            if (!empty(\Yii::$app->user->identity->peramida_name)) {
                $this->piramida = ['name' => \Yii::$app->user->identity->peramida_name, 'id' => \Yii::$app->user->identity->session_id];
            }
        }
        return parent::beforeAction($action);
    }


    /**
     * Lists all MessageThemes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MessageThemes::find()->where(['!=', 'hidden', 1]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new MessageThemes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Messages();

        $model->scenario = Messages::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post())) {
            $model->status_id = MessageStatuses::RECD;
            $model->filesUpload = UploadedFile::getInstances($model, 'filesUpload');
            if ($model->save()) {
                if ($model->filesUpload) {
                    $folderId = $model->id;
                    $uploadDirectory = 'uploads/tickets/' . $folderId;

                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }

                    $paths = $model->uploadFiles($folderId);
                    if ($paths !== false) {
                        $model->files = json_encode($paths);
                    }
                }
                if ($model->save()) {
                    MessageHistory::setNewMessage($model->id);

                    if ($fileName = $model->sendAdminNoticeEmail()) {
                        unlink($fileName);
                    }
                    $text = 'Ваше обращение успешно получено. Мы зарегистрируем его и сообщим Вам номер.';
                    if (!empty($email = ($model->email) ? $model->email : $model->getUser()->one()->email)) {
                        $model->sendNoticeEmail('Обращение в Р-Энергия', $text, $email);
                    } elseif (!empty($phone = ($model->phone) ? $model->phone : $model->getUser()->one()->phone)) {
                        $model->sendNoticeSms($text, $phone);
                    }

                    Yii::$app->session->setFlash('success', 'Ваше заявление успешно сформировано! В разделе «Диалоги» Вы можете отслеживать статус его рассмотрения.');
                    return $this->redirect(['messages/update', 'id' => $model->id]);
                }
            }
        }

        $data = ['id' => \Yii::$app->user->identity->id_db];
        $proifileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/profile', $data);

        if (isset($proifileInfo['success'])) {
            $profileInfo = $proifileInfo['success'];
        } else {
            return $proifileInfo['error'];
        }
        return $this->render('create', [
            'themeModel' => $this->findMessageThemes($id),
            'messageModel' => $model,
            'profileInfo' => $profileInfo,
            'userModel' => \Yii::$app->user->identity,
        ]);
    }

    public function actionGeneratePdf()
    {
        if (Yii::$app->request->isPost) {
            $model = new Messages();

            // Загрузка данных формы
            if ($model->load(Yii::$app->request->post())) {
                $fileName = time() . 'Обращение.pdf';
                $model->generatePdf($fileName);

                $pdfPath = Yii::getAlias('@webroot/temp_pdf/' . $fileName);
                if (file_exists($pdfPath)) {
                    return $this->asJson([
                        'status' => 'success',
                        'pdfUrl' => Yii::getAlias('@web') . '/web/temp_pdf/' . $fileName,
                    ]);
                } else {
                    return $this->asJson([
                        'status' => 'error',
                        'message' => 'PDF файл не найден',
                    ]);
                }
            }
        }

        return $this->asJson([
            'status' => 'error',
            'message' => 'Данные не получены',
        ]);
    }

    /**
     * Finds the MessageThemes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageThemes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMessageThemes($id)
    {
        if (($model = MessageThemes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the MessageThemes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMessage($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function sendToServer($url, $data = array(), $toArray = true, $method = 'GET')
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setData($data)
            ->send();
        if ($response->isOk) {
            if ($toArray) {
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            } else {
                return ['success' => $response];
            }
        } else {
            $this->redirect(['err/one-c']);
        }
    }
}
