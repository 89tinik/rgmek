<?php

namespace app\controllers;

use app\models\MessageHistory;
use Yii;
use app\models\Messages;
use app\models\MessagesSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
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
     * Lists all Messages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessagesSearch();
        $dataProvider = $searchModel->searchForUser(Yii::$app->request->queryParams, \Yii::$app->user->identity->getId());
        $dataProvider->pagination->pageSize = 5;

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_list', [
                'dataProvider' => $dataProvider,
            ]);
        }

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


        $model->scenario = Messages::SCENARIO_USER_UPDATE;
        $model->new = 0;
        if ($model->save()) {
            if ($model->load(Yii::$app->request->post())) {
                $model->filesUpload = UploadedFile::getInstances($model, 'filesUpload');

                if ($model->filesUpload) {
                    $folderId = $model->id;
                    $uploadDirectory = 'uploads/tickets/' . $folderId;

                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }

                    $paths = $model->uploadFiles($folderId);
                    $oldFilesArr = json_decode($model->files, true);
                    $allFilesArr = array_merge($oldFilesArr, $paths);
                    if ($paths !== false) {
                        $model->files = json_encode($allFilesArr);
                    }
                }
                if (!$model->save()) {
                    return 'Ошибка!';
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Messages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReCall($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Messages::SCENARIO_USER_UPDATE;
        $model->status_id = 4;
        if ($model->save()) {
            $modelHistory = new MessageHistory();
            $modelHistory->log = 'Запрос отозван пользователемж';
            $modelHistory->message_id = $model->id;
            $modelHistory->save();

            return $this->redirect(['index']);
        }
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
        if (($model = Messages::findOne(['id' => $id, 'user_id' => \Yii::$app->user->identity->getId()])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}