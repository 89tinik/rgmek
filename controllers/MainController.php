<?php


namespace app\controllers;


use app\models\InstallESForm;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\helpers\Url;


class MainController extends Controller
{

    public $layout = 'default';
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

    public function actionIndex()
    {
        $data = ['id' => \Yii::$app->user->identity->id_db];
        //$data = ['id' => 'NjIyODAwMDM1MS02Mg=='];
        $profileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts_list', $data);
        if (isset($profileInfo['success'])){
            return $this->render('index', ['result'=>$profileInfo['success']]);
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
            $file_name = \Yii::$app->user->identity->id.'_'.$edoInfo['success']['Name'];
            if ($this->decodingToDocSave($edoInfo['success']['File'], $file_name)){
                return $this->redirect(Url::home(true).'web/temp_edo/'.$file_name, 301);
            }
        } else {
            return $edoInfo['error'];
        }

    }

    public function actionArrear()
    {
        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $arrearInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account/', $data);
        if (isset($arrearInfo['success'])){
            if ($arrearInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
            return $this->render('arrear', ['result'=>$arrearInfo['success']]);
        } else {
            return $arrearInfo['error'];
        }
    }
    public function actionAllArrear()
    {
        $this->layout = 'ajax';
        $data = ['uidcontracts' => \Yii::$app->request->get('uid'), 'quantity' => 'full'];
        $arrearInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account/', $data);

        if (isset($arrearInfo['success'])){
            if ($arrearInfo['success']['ID'] != \Yii::$app->user->identity->id_db){
                throw new HttpException(403, 'Доступ запрещён');
            }
            return $this->render('allArrear', ['result'=>$arrearInfo['success']['Account']]);
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
//            $client = new Client();
//            $request = $client->createRequest()
//                ->setMethod('get')
//                ->setUrl($invoiceInfo['success']['URL']);
//            $request->on(Request::EVENT_AFTER_SEND, function (RequestEvent $event) {
//                $data = $event->response->getData();
//
//                $data['content'] = base64_decode($data['encoded_content']);
//
//                $event->response->setData($data);
//            });
//
//            return $request->send();

           // return file_get_contents($invoiceInfo['success']['URL']);
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

        return $this->render('payment');
    }

    public function actionIndication()
    {
        return $this->render('indication');
    }

    public function actionInvoice()
    {
        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $invoiceData = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/package_documents', $data);
        if (isset($invoiceData['success'])){
            return $this->render('invoice', ['result'=>$invoiceData['success']]);
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