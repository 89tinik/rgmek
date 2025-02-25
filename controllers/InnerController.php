<?php


namespace app\controllers;


use app\models\Contract;
use app\models\FeedbackForm;
use app\models\Page;
use app\models\Theme;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use yii\web\UploadedFile;

class InnerController extends Controller
{

    public $layout = 'inner';
    public $userName = '';
    public $listContract = '';
    public $piramida = [];

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
if(isset(\Yii::$app->user->identity->full_name)) {
    $this->userName = \Yii::$app->user->identity->full_name;

    if (!empty(\Yii::$app->user->identity->peramida_name)) {
        $this->piramida = ['name' => \Yii::$app->user->identity->peramida_name, 'id' => \Yii::$app->user->identity->session_id];
    }
}
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
                    Yii::$app->session->setFlash('success', 'Ваше сообщение успешно отправлено!');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Что-то пошло не так - повторите попытку позже!');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации - проверьте Ваши данные!');
            }

        }

        $allThemes=Theme::find()->orderBy(['sort' => SORT_ASC,'id'=>SORT_DESC])->all(); // Query(by Active record or Query Builder or ..)

        $itemThemes=ArrayHelper::map($allThemes,'title','title');
        if (\Yii::$app->request->get('tehadd') == 'true'){
            $model->subject = 'Технологическое присоединение';
        }
        $contracts = '';
        if (!empty($contractsArr = Contract::find()->where(['user_id'=>\Yii::$app->user->identity->id])->asArray()->all())){
            foreach ($contractsArr as $contract){
                if(empty($contracts)){
                    $contracts = $contract['full_name'];
                } else {
                    $contracts .= ';'.$contract['full_name'];
                }
            }
        }
        return $this->render('fos', [
            'model' => $model,
            'itemThemes' => $itemThemes,
            'contracts' => $contracts
        ]);
    }

    public function actionHelp(){
        $pages = Page::findOne(1);
        return $this->render('help',[
            'page'=>$pages,
        ]);
    }

    public function actionPaySuccess(){
        return $this->render('paySuccess');
    }
    public function actionPayFail(){
        return $this->render('payFail');
    }
    public function actionInformationTehAdd(){
        return $this->render('informationTehAdd');
    }

    public function actionDownloadReceipt()
    {

        $data = ['uidpaydoc' => \Yii::$app->request->get('uidpaydoc')];
        $receiptInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_pay_document', $data);
        if (isset($receiptInfo['success'])){
            if ($receiptInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }

            $options=[];
            if( \Yii::$app->request->get('print') == 'true'){
                $options=['inline' => true, 'mimeType' => 'application/pdf'];
            }

            return \Yii::$app->response->sendContentAsFile(base64_decode ($receiptInfo['success']['File']), $receiptInfo['success']['Name'], $options);
        } else {
            return $receiptInfo['error'];
        }

    }
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if ($exception->statusCode == 404)
                return $this->render('error404', ['exception' => $exception]);
            else
                return $this->render('error', ['exception' => $exception]);
        }
    }

    private function sendToServer ($url, $data=array(), $toArray=true, $method='GET'){
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setData($data)
            ->send();
        if ($response->isOk) {
            if ($toArray){
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            }else{
                return ['success' => $response];
            }
        } else {
            $this->redirect(['err/one-c']);
        }
    }
}