<?php

namespace app\modules\ticket\controllers;

use app\models\MessageHistory;
use app\models\Messages;
use app\models\MessagesSearch;
use app\models\MessageStatuses;
use app\models\User;
use app\modules\ticket\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Default controller for the `ticket` module
 */
class DefaultController extends Controller
{
    public $userName = '';


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],

                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->userName = Yii::$app->user->identity->username;
        return parent::beforeAction($action);
    }


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/ticket/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MessagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Messages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Messages::SCENARIO_ADMIN_UPDATE;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->dirtyAttributes) {
                $model->new = 1;
                $model->update = date("Y-m-d H:i:s");
                if ($model->status_id < $this->findModel($model->id)->status_id) {
                    $model->status_id = $this->findModel($model->id)->status_id;
                }
                $model->answerFilesUpload = UploadedFile::getInstances($model, 'answerFilesUpload');

                if ($model->answerFilesUpload) {
                    $folderId = $model->id;
                    $uploadDirectory = 'uploads/tickets/' . $folderId;

                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }

                    $paths = $model->uploadFiles($folderId, 'answerFilesUpload');
                    if ($paths !== false) {
                        $model->answer_files = json_encode($paths);
                    }
                }
                $historyArr = [];
                $link = Yii::$app->urlManager->createAbsoluteUrl(['/messages/update', 'id' => $id]);

                if ($model->status_id != $this->findModel($model->id)->status_id && $model->status_id == MessageStatuses::CLOSE) {
                    $historyArr[] = 'Установлен статус "' . $model->getStatus()->one()->status . '";';
                    $subject = 'Ваше обращение отозвано.';
                    $text = 'Уважаемый клиент! Вашему обращение номер №'.
                        $model->admin_num . ' от ' . Yii::$app->formatter->asDate($model->published, 'php:d.m.Y') .
                        ' отозвано администратором.';
                } elseif ($model->admin_num != $this->findModel($model->id)->admin_num  && !empty($model->admin_num)) {
                    $historyArr[] = 'Принято в работу';
                    $subject = 'Ваше обращение зарегистрировано.';
                    $text = 'Уважаемый клиент! Вашему обращению присвоен регистрационный номер №'.
                        $model->admin_num . ' от ' . Yii::$app->formatter->asDate($model->published, 'php:d.m.Y') .
                    '. Обращение принято в работу. Подробности можете узнать, перейдя по ' .
                    Html::a('ссылке', $link);
                } elseif ($model->status_id != $this->findModel($model->id)->status_id) {
                    $historyArr[] = $model->getStatus()->one()->status ;
                    $subject = 'Статус обращения № ' . $model->admin_num .
                        ' от ' . Yii::$app->formatter->asDate($model->published, 'php:d.m.Y') .
                        ' изменён на ' . $model->getStatus()->one()->status;
                    $text = 'Подробности можете узнать перейдя по ' . Html::a('ссылке', $link);
                } elseif ($model->isAttributeChanged('answer') && !empty($model->answer)) {
                    $historyArr[] = 'Дан ответ';
                    $subject = 'Получен ответ на обращение № ' . $model->admin_num . 'от ' . Yii::$app->formatter->asDate($model->published, 'php:d.m.Y');
                    $text = 'Уважаемый клиент! В Личном кабинете размещен ответ ООО «РГМЭК» на Ваше обращение. Для просмотра перейдите по ' . Html::a('ссылке', $link);
                }

                if ($model->save()) {
                    if (!empty($historyArr)) {
                        foreach ($historyArr as $log) {
                            $modelHistory = new MessageHistory();
                            $modelHistory->log = $log;
                            $modelHistory->message_id = $model->id;
                            $modelHistory->created = date('Y-m-d H:i:s');
                            $modelHistory->save();
                        }
                        if (!empty($email = ($model->email) ? $model->email : $model->getUser()->one()->email)) {
                            if ($model->sendNoticeEmail($subject, $text, $email) === true) {
                                Yii::$app->session->setFlash('success', 'Обновлено');

                            }
                        } elseif (!empty($phone = ($model->phone) ? $model->phone : $model->getUser()->one()->phone)) {
                            if ($model->sendNoticeSms($subject, $phone) === true) {
                                Yii::$app->session->setFlash('success', 'Обновлено');
                            }
                        }
                    }
                    return $this->redirect(['/ticket/index']);
                }
            }
        }

        $userModel = User::findIdentity($model->user_id);
        $data = ['id' => $userModel->id_db];
        $proifileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/profile', $data);

        if (isset($proifileInfo['success'])) {
            $profileInfo = $proifileInfo['success'];
        } else {
            return $proifileInfo['error'];
        }

        return $this->render('update', [
            'model' => $model,
            'profileInfo' => $profileInfo
        ]);
    }

    public function actionStatistic()
    {
        $searchModel = new MessagesSearch();

        $dataProvider = $searchModel->searchStatistics(Yii::$app->request->queryParams);

        return $this->render('statistic', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing MessageThemes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionLogout()
    {

        Yii::$app->user->logout();
        return $this->redirect('/ticket/login');
    }

    /**
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
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
