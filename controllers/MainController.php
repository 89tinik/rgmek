<?php


namespace app\controllers;


use app\models\AttachForm;
use app\models\ConsumptionForm;
use app\models\Contract;
use app\models\HistoryForm;
use app\models\InstallESForm;
use app\models\ReceiptForm;
use app\models\User;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\web\UploadedFile;


class MainController extends Controller
{

    public $layout = 'default';
    public $userName = '';
    public $withDate = '';
    public $byDate = '';
    public $listContract = '';
    public $currentContract = '';
    public $currentContractStatus = '';

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
        $curentContract = Contract::find()->where(['uid'=> \Yii::$app->request->get('uid')])->one();
        if (empty($curentContract)){
            $requestForm = \Yii::$app->request->get();
            if (is_array($requestForm[array_key_first($requestForm)])){
                $curentContract = Contract::find()->where(['uid'=> $requestForm[array_key_first($requestForm)]['uid']])->one();
            }
        }
        $this->currentContract =$curentContract->full_name;
        $this->currentContractStatus =$curentContract->status_name;

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->withDate = \Yii::$app->user->identity->with_date;
        $this->byDate = \Yii::$app->user->identity->by_date;

        $data = ['id' => \Yii::$app->user->identity->id_db];
        //$data = ['id' => 'NjIyODAwMDM1MS02Mg=='];
        $profileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts_list', $data);
        if (isset($profileInfo['success'])){
            return $this->render('index', [
                'result'=>$profileInfo['success'],
                'withDate'=>\Yii::$app->user->identity->with_date,
                'byDate'=>\Yii::$app->user->identity->by_date
            ]);
        } else {
            return $profileInfo['error'];
        }
    }

    public function actionProfile()
    {
        $data = ['id' => \Yii::$app->user->identity->id_db];
        $proifileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/profile', $data);

        if (isset($proifileInfo['success'])){
            return $this->render('profile', ['result'=>$proifileInfo['success']]);
        } else {
            return $proifileInfo['error'];
        }

    }

    public function actionEdo()
    {

        $this->listContract = Contract::getListContracts(\Yii::$app->user->identity->id);

        $installESForm = new InstallESForm();

        $buttonText = 'Подписаться';
        $invoiceEmail = false;
        if(!empty(\Yii::$app->request->get('currentEmail'))){
            \Yii::$app->session->setFlash('success','Ящик для рассылки электронных счетов - '.\Yii::$app->request->get('currentEmail'));
            $buttonText = 'Изменить';
            $invoiceEmail = true;
        }

        if ($installESForm->load(\Yii::$app->request->post()) && $installESForm->validate()) {
            $data = ['id' => \Yii::$app->user->identity->id_db,
                'value'=> $installESForm->email];
            $setEmail = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/add_mail', $data);


            if (isset($setEmail['success'])){
                \Yii::$app->session->setFlash('success', $setEmail['success']['Message']);
                $buttonText = 'Изменить';
                $invoiceEmail = true;
            } else {
                \Yii::$app->session->setFlash('error', $setEmail['error']);
            }
        }
        return $this->render('edo', compact('installESForm','buttonText', 'invoiceEmail'));

    }

    public function actionDownloadedo()
    {

        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $edoInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_edo', $data);
        if (isset($edoInfo['success'])){
            if ($edoInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
//            $file_name = \Yii::$app->user->identity->id.'_'.$edoInfo['success']['Name'];
//            if ($this->decodingToDocSave($edoInfo['success']['File'], $file_name)){
//                return $this->redirect(Url::home(true).'web/temp_edo/'.$file_name, 301);
//            }
            return \Yii::$app->response->sendContentAsFile(base64_decode ($edoInfo['success']['File']), $edoInfo['success']['Name']);
        } else {
            return $edoInfo['error'];
        }

    }

    public function actionArrear()
    {
        $model = new ReceiptForm();


        if ($model->load(\Yii::$app->request->post()) && $receipt=$model->addReceipt()) {
            $price = $receipt->ee + $receipt->penalty;
            $invoice = \pantera\yii2\pay\sberbank\models\Invoice::addSberbank($receipt->id, $price);
            $this->redirect(['/sberbank/default/create', 'id' => $invoice->id]);
        }



        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $arrearInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account/', $data);
        if (isset($arrearInfo['success'])){
            if ($arrearInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
            return $this->render('arrear', ['result'=>$arrearInfo['success'], 'model'=>$model]);
        } else {
            return $arrearInfo['error'];
        }
    }


    public function actionObjects()
    {
        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $arrearInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/objects_list', $data);

        if (isset($arrearInfo['success'])){
            if ($arrearInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
            return $this->render('objects', ['result'=>$arrearInfo['success']]);
        } else {
            return $arrearInfo['error'];
        }
    }

    public function actionAccessFile()
    {

        $data = \Yii::$app->request->get();
        //$data = ['uid' => '899d2100-6c34-11eb-929b-002590c76e1b', 'print' => \Yii::$app->request->get('print')];
        $fileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_check/'.\Yii::$app->request->get('action'), $data);
        if (isset($fileInfo['success'])){
            if ($fileInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }

            $options=[];
            if($data['print'] == 'true'){
                $options=['inline' => true, 'mimeType' => 'application/pdf'];
            }

            return \Yii::$app->response->sendContentAsFile(file_get_contents($fileInfo['success']['URL']), $fileInfo['success']['Name'], $options);

            //return $this->redirect($invoiceInfo['success']['URL'], 301);
        } else {
            return $fileInfo['error'];
        }

    }
    
    public function actionAccessHistoryFile()
    {

        $data = \Yii::$app->request->get();
        $historyInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_check/'.\Yii::$app->request->get('action'), $data);
        if (isset($historyInfo['success'])){
            if ($historyInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
            if ($data['print'] == 'true'){
                return \Yii::$app->response->sendContentAsFile(base64_decode ($historyInfo['success']['FilePDF']), $historyInfo['success']['Name'].'.pdf', ['inline' => true, 'mimeType' => 'application/pdf']);
            } else {
                return \Yii::$app->response->sendContentAsFile(base64_decode ($historyInfo['success']['FileXLS']), $historyInfo['success']['Name'].'.xls');
            }
        } else {
            return $historyInfo['error'];
        }

    }

    public function actionAccessTehaddFile()
    {

        $data = \Yii::$app->request->get();
//        $fileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_check/download_technological', $data, true, 'POST');
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_check/download_technological')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $responseArr = $xml->parse($response);
            if (isset($responseArr['Error'])){
                return $responseArr['Error']['Message'];
            } else {
                return \Yii::$app->response->sendContentAsFile(base64_decode ($responseArr['File']), $responseArr['Name'].'.'.$responseArr['Extension']);
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }
//        if (isset($historyInfo['success'])){
//            if ($historyInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
//                throw new HttpException(403, 'Доступ запрещён');
//            }
//            if ($data['print'] == 'true'){
//                return \Yii::$app->response->sendContentAsFile(base64_decode ($historyInfo['success']['FilePDF']), $historyInfo['success']['Name'].'.pdf', ['inline' => true, 'mimeType' => 'application/pdf']);
//            } else {
//                return \Yii::$app->response->sendContentAsFile(base64_decode ($historyInfo['success']['FileXLS']), $historyInfo['success']['Name'].'.xls');
//            }
//        } else {
//            return $historyInfo['error'];
//        }

    }

//    public function actionDecoding()
//    {
//
//        $data = ['uidaccounts' => \Yii::$app->request->get('uid')];
//        $invoiceInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_account', $data);
//        if (isset($invoiceInfo['success'])){
//            return $this->decodingToPdfSave($invoiceInfo['success']['File']);
//        } else {
//            return $invoiceInfo['error'];
//        }
//
//    }

    public function actionPayment()
    {

        return $this->render('payment',[
            'withDate'=>\Yii::$app->user->identity->with_date,
            'byDate'=>\Yii::$app->user->identity->by_date,
            'typeOrder'=>\Yii::$app->request->get('type-order')]);
    }

    public function actionIndication()
    {
        $model = new AttachForm();

        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $indicationData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/objects_list_ind', $data);
        if (isset($indicationData['success'])){
            return $this->render('indication', [
                'result'=>$indicationData['success'],
                'model'=>$model
            ]);
        } else {
            return $indicationData['error'];
        }

    }

    public function actionInvoice()
    {
        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $invoiceData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/package_documents', $data);
        if (isset($invoiceData['success'])){
            $withDateArr = explode('.', \Yii::$app->user->identity->with_date);
            $mounth = $withDateArr[1] - 1;
            if($mounth == 0){
                $mounth = 12;
                $year = $withDateArr[2] - 1;
            } else {
                $year = $withDateArr[2];
            }
            $mounth = ($mounth < 10)?'0'.$mounth:$mounth;
            $withDateDetail = '01.'.$mounth.'.'.$year;

            if (\Yii::$app->request->get('type-order') == 'invoices'){
                $data['withdate']=\Yii::$app->user->identity->with_date;
                $data['bydate']=\Yii::$app->user->identity->by_date;
                $invoicesList = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/invoices', $data);
                if ($invoicesList['success']){
                    $invoices = $invoicesList['success'];
                } else {
                    $invoices = $invoicesList['error'];
                }
            }


            return $this->render('invoice', [
                'result'=>$invoiceData['success'],
                'withDate'=>\Yii::$app->user->identity->with_date,
                'withDateDetail'=>$withDateDetail,
                'byDate'=>\Yii::$app->user->identity->by_date,
                'invoices'=>$invoices,
                'typeOrder'=>\Yii::$app->request->get('type-order')
            ]);
        } else {
            return $invoiceData['error'];
        }
    }

    public function actionHistory()
    {
        //http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/history_ind?uidobject=ceaffecb-9e7c-11e4-9c77-001e8c2d263f&uidtu=896f5aff-9f8e-11e4-9c77-001e8c2d263f&withdate=01.10.2021&bydate=13.05.2022
        $model = new HistoryForm();
        if (!$model->load(\Yii::$app->request->get())) {
            $model->uidobject = \Yii::$app->request->get('uidobject');
            $model->uidtu = \Yii::$app->request->get('uidtu');
            $model->uid = \Yii::$app->request->get('uid');
        }
        if (empty( $model->bydate = \Yii::$app->request->get('HistoryForm')['bydate'])) {
            $bydateArr = explode('.', \Yii::$app->user->identity->by_date);
            $model->bydate = '01.' . $bydateArr[1] .'.'. $bydateArr[2];
        }
        if (empty($model->withdate)) {
            $bydateArr = explode('.', $model->bydate);
            $Y = $bydateArr[2] - 1;
            $model->withdate = '01.' . $bydateArr[1] . '.' . $Y;
        }
        if ($model->validate()) {
            $data = [
                'uidobject' => $model->uidobject,
                'withdate' => $model->withdate,
                'bydate' => $model->bydate
            ];
            $historyData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/history_ind', $data);
            if (isset($historyData['success'])) {
                return $this->render('history', [
                    'result' => $historyData['success'],
                    'currentTU' => $model->uidtu,
                    'model' => $model
                ]);
            } else {
                return $historyData['error'];
            }
        } else {
            return 'Ошибка валидации - проверьте Ваши данные!';
        }
    }

    public function actionConsumption (){
        $model = new ConsumptionForm();
        if (!$model->load(\Yii::$app->request->get())) {
            $model->uidtu = \Yii::$app->request->get('uidtu');
            $model->uid = \Yii::$app->request->get('uid');
        }
        if (empty( $model->bydate = \Yii::$app->request->get('ConsumptionForm')['bydate'])) {
            //$model->bydate = '01.12.'. date('Y');
            $model->bydate = \Yii::$app->user->identity->by_date;
        }
        if (empty($model->withdate)) {
            $bydateArr = explode('.', $model->bydate);
            $Y = $bydateArr[2] - 1;
            $model->withdate = '01.01.'. date('Y');
        }
        $data = [
            'uidcontract' => $model->uid,
            'withdate' => $model->withdate,
            'bydate' => $model->bydate
        ];
        $objectsData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/report_consumption', $data);
        if (isset($objectsData['success'])){
            return $this->render('consumption', [
            'objectsData' => $objectsData['success'],
                'model' => $model
            ]);
        } else {
            return $objectsData['error'];
        }
    }
    
    
    public function actionRaz (){
        return $this->render('raz', []);
    }
    
    public function actionTehadd (){
        $data = [
            'uidcontracts' => \Yii::$app->request->get('uid')
        ];
        $objectsData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/applications_list/technological', $data);
        if (isset($objectsData['success'])){
            return $this->render('tehadd', [
                'objectsData' => $objectsData['success']
            ]);
        } else {
            return $objectsData['error'];
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
            return ['error'=>'Не удалось связаться БД - повторите попытку позже.'];
        }
    }



//    private function decodingToPdf ($base_64){
//        // Real date format (xxx-xx-xx)
//        $toDay   = date("Y-m-d");
//
//        // we give the file a random name
//        $name    = "archive_".$toDay."_XXXXX_.pdf";
//
//        // a route is created, (it must already be created in its repository(pdf)).
//        $rute    = "pdf/".$name;
//
//        // decode base64
//        $pdf_b64 = base64_decode($base_64);
//
//        // you record the file in existing folder
//        if(file_put_contents($rute, $pdf_b64)){
//            //just to force download by the browser
//            header("Content-type: application/pdf");
//
//            //print base64 decoded
//            echo $pdf_b64;
//        }
//    }

//    private function decodingToPdfSave ($base_64){
////        $pdf_base64 = "base64pdf.txt";
//////Get File content from txt file
////        $pdf_base64_handler = fopen($pdf_base64,'r');
////        $pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
////        fclose ($pdf_base64_handler);
//////Decode pdf content
//        $pdf_decoded = base64_decode ($base_64);
////Write data back to pdf file
//        $pdf = fopen ('test.pdf','w');
//        fwrite ($pdf,$pdf_decoded);
////close output file
//        fclose ($pdf);
//        echo 'Done';
//    }
//
private function decodingToDocSave ($base_64, $file_name){
        $doc_decoded = base64_decode ($base_64);
//Write data back to pdf file
        $doc = fopen ('temp_edo/'.$file_name,'w');
        fwrite ($doc,$doc_decoded);
//close output file
        fclose ($doc);
        return true;
    }
}