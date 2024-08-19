<?php

namespace app\controllers;

use app\models\Messages;
use Yii;
use app\models\MessageThemes;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
            'query' => MessageThemes::find(),
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
            $model->status_id = 1;
            $model->filesUpload = UploadedFile::getInstances($model, 'filesUpload');

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
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }


        return $this->render('create', [
            'themeModel' => $this->findMessageThemes($id),
            'messageModel' => $model,
            'userModel' => \Yii::$app->user->identity,
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
        $model = $this->findMessage($id);

        $model->scenario = Messages::SCENARIO_USER_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
}
