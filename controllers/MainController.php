<?php


namespace app\controllers;


use app\models\AttachForm;
use app\models\Contract;
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
        $this->currentContract =$curentContract->full_name;

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

        $buttonText = '??????????????????????';
        $invoiceEmail = false;
        if(!empty(\Yii::$app->request->get('currentEmail'))){
            \Yii::$app->session->setFlash('success','???????? ?????? ???????????????? ?????????????????????? ???????????? - '.\Yii::$app->request->get('currentEmail'));
            $buttonText = '????????????????';
            $invoiceEmail = true;
        }

        if ($installESForm->load(\Yii::$app->request->post()) && $installESForm->validate()) {
            $data = ['id' => \Yii::$app->user->identity->id_db,
                'value'=> $installESForm->email];
            $setEmail = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/add_mail', $data);


            if (isset($setEmail['success'])){
                \Yii::$app->session->setFlash('success', $setEmail['success']['Message']);
                $buttonText = '????????????????';
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
                throw new HttpException(403, '???????????? ????????????????');
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
                throw new HttpException(403, '???????????? ????????????????');
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
                throw new HttpException(403, '???????????? ????????????????');
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
                throw new HttpException(403, '???????????? ????????????????');
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
            return ['error'=>'???? ?????????????? ?????????????????? ???? - ?????????????????? ?????????????? ??????????.'];
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