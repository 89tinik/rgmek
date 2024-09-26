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

                    $allFilesArr = $model->uploadFiles($folderId);
                    if ($oldFilesArr = json_decode($model->files, true)) {
                        $allFilesArr = array_merge($oldFilesArr, $allFilesArr);
                    }
                    if ($allFilesArr !== false) {
                        $model->files = json_encode($allFilesArr);
                    }
                }
                if (!$model->save()) {
                    return 'Ошибка!';
                } else {
                    $subject = 'Обновлено обращение';
                    if ($model->admin_num) {
                        $subject .= ' №' . $model->admin_num;
                    } else {
                        $subject .= ' №(не задано)';
                    }
                    $subject .= ' (id ' . $model->id . ')';
                    if ($fileName = $model->sendAdminNoticeEmail($subject)) {
                        unlink($fileName);
                    }
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
            $text = 'Уважаемый клиент! Вы отозвали обращение № 3040 от 26.09.2024. Благодарим за пользование личным кабинетом!';
            if (!empty($email = ($model->email) ? $model->email : $model->getUser()->one()->email)) {
                $model->sendNoticeEmail('Ваше обращение отозвано.', $text, $email);
            } elseif (!empty($phone = ($model->phone) ? $model->phone : $model->getUser()->one()->phone)) {
                $model->sendNoticeSms($text, $phone);
            }
            $modelHistory = new MessageHistory();
            $modelHistory->log = 'Запрос отозван пользователем';
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
