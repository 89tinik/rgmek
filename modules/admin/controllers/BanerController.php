<?php

namespace app\modules\admin\controllers;

use app\models\UpdateBanerForm;
use app\models\UploadForm;
use Yii;
use app\models\Baner;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BanerController implements the CRUD actions for Baner model.
 */
class BanerController extends Controller
{
    public $userName = '';

    /**
     * {@inheritdoc}
     */
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
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->userName = \Yii::$app->user->identity->username;
        return parent::beforeAction($action);
    }

    /**
     * Lists all Baner models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'baners' => Baner::find()->orderBy(['sort' => SORT_ASC,'id'=>SORT_DESC])->all()
        ]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        $model->disable = 0;
        $model->sort = 1;
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->path = UploadedFile::getInstance($model, 'path');
            if ($model->upload()) {
                return $this->redirect(['/admin/baner']);
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    /**
     * Deletes an existing Baner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteFile();
        return $this->redirect(['/admin/baner']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $updateForm = new UpdateBanerForm();
        if ($up = $updateForm->load(Yii::$app->request->post())) {
            if ($updateForm->validate() && $updateForm->updateBaner()) {
                return $this->redirect(['/admin/baner']);
            } else {
                $errors = print_r($updateForm->errors, true);
                Yii::$app->session->setFlash('error', 'Ошибка валидации!!!' . $errors);
            }
        } else {
            $updateForm->load(
                ['link' => $model->link, 'id' => $model->id, 'disable' => $model->disable, 'sort' => $model->sort],
                ''
            );
        }

        return $this->render('update', [
            'model' => $updateForm,
        ]);
    }

    /**
     * Finds the Baner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Baner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Baner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
