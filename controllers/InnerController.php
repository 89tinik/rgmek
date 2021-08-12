<?php


namespace app\controllers;


use app\models\Contract;
use app\models\FeedbackForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;

class InnerController extends Controller
{

    public $layout = 'inner';
    public $userName = '';
    public $listContract = '';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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

        $this->userName = \Yii::$app->user->identity->full_name;

        return parent::beforeAction($action);
    }

    public function actionFos (){
       // $this->listContract = Contract::getListContracts(\Yii::$app->user->identity->id);
        $model = new FeedbackForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()){
                $model->file = UploadedFile::getInstances($model, 'file');
                $files = $model->file;

                if ($model->sendMail(['89.tinik@gmail.com',Yii::$app->params['adminEmail']], $files)) {
                    Yii::$app->session->setFlash('success', 'Ваши данные успешно отправлены!');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Что-то пошло не так - повторите попытку позже!');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации - проверьте Ваши данные!');
            }

        }
        return $this->render('fos', [
            'model' => $model,
        ]);
    }

    public function actionHelp(){
        return $this->render('help');
    }

    public function actionPaySuccess(){
        return $this->render('paySuccess');
    }
    public function actionPayFail(){
        return $this->render('payFail');
    }
}