<?php

namespace app\modules\ticket\controllers;

use app\models\MessageHistory;
use app\models\Messages;
use app\models\MessagesSearch;
use app\modules\ticket\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
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
                if ($model->isAttributeChanged('status_id')) {
                    $historyArr[] = 'Установлен статус "'.$model->getStatus()->one()->status.'";';
                    $subject = 'Статус обращения № ' . $model->admin_num . ' изменён на ' . $model->getStatus()->one()->status;
                }
                if ($model->isAttributeChanged('answer') && !empty($model->answer)) {
                    $historyArr[] = 'Добавлен ответ;';
                    $subject = 'Получен ответ на обращение № ' . $model->admin_num;
                }

                if ($model->save()) {
                    if (!empty($historyArr)) {
                        foreach ($historyArr as $log) {
                            $modelHistory = new MessageHistory();
                            $modelHistory->log = $log;
                            $modelHistory->message_id = $model->id;
                            $modelHistory->save();
                        }
                    }

                    if (!empty($email = ($model->email) ?? $model->getUser()->one()->email)) {
                        $link = Yii::$app->urlManager->createAbsoluteUrl(['/messages/update', 'id' => $id]);
                        $text = 'Подробности можете узнать перейдя по ' . Html::a('ссылке', $link);
                        if ($message = $model->sendNoticeEmail($subject, $text, $email) === true) {
                            $message = 'Обновлено!';
                        }
                    } elseif (!empty($phone = ($model->phone) ?? $model->getUser()->one()->phone)) {
                        if ($message = $model->sendNoticeSms($subject, $phone) === true) {
                            $message = 'Обновлено!';
                        }
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'message' => $message,
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
}
