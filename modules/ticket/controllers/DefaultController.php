<?php

namespace app\modules\ticket\controllers;

use app\models\Messages;
use app\models\MessagesSearch;
use app\modules\ticket\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
            if ($model->status_id < $this->findModel($model->id)->status_id){
                $model->status_id = $this->findModel($model->id)->status_id;
            }
            $model->answerFilesUpload = UploadedFile::getInstances($model, 'answerFilesUpload');

            if ($model->answerFilesUpload) {
                $folderId = $model->id;
                $uploadDirectory = 'uploads/tickets/' . $folderId;

                // Создаем директорию, если она не существует
                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                // Загружаем файлы и сохраняем пути в формате JSON
                $paths = $model->uploadFiles($folderId);
                if ($paths !== false) {
                    $model->answer_files = json_encode($paths);
                }
            }
            if ($model->save()) {
                $message = 'Обновлено!';
            }
        }

        return $this->render('update', [
            'model' => $model,
            'message' => $message,
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
