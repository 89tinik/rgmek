<?php


namespace app\controllers;


use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\HttpException;

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
        $prifileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts_list', $data);
        if (isset($prifileInfo['success'])){
            return $this->render('index', ['result'=>$prifileInfo['success']]);
        } else {
            return $prifileInfo['error'];
        }
    }

    public function actionProfile()
    {
        $data = ['id' => \Yii::$app->user->identity->id_db];
        $proifileInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/profile', $data);
//        var_dump($proifileInfo);
//        die();
        if (isset($proifileInfo['success'])){
            return $this->render('profile', ['result'=>$proifileInfo['success']]);
        } else {
            return $proifileInfo['error'];
        }

    }

    public function actionArrear()
    {
        $data = ['uidcontracts' => \Yii::$app->request->get('uid')];
        $arrearInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account/', $data);
//        var_dump($arrearInfo);
//        die();
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

    public function actionDecoding()
    {

        $data = ['uidaccounts' => \Yii::$app->request->get('uid')];
        $invoiceInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/download_account', $data);
        if (isset($invoiceInfo['success'])){
            return $this->decodingToPdfSave($invoiceInfo['success']['File']);
        } else {
            return $invoiceInfo['error'];
        }

    }

    public function actionPayment()
    {
        return $this->render('payment');
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

    private function decodingToPdf ($base_64){
        // Real date format (xxx-xx-xx)
        $toDay   = date("Y-m-d");

        // we give the file a random name
        $name    = "archive_".$toDay."_XXXXX_.pdf";

        // a route is created, (it must already be created in its repository(pdf)).
        $rute    = "pdf/".$name;

        // decode base64
        $pdf_b64 = base64_decode($base_64);

        // you record the file in existing folder
        if(file_put_contents($rute, $pdf_b64)){
            //just to force download by the browser
            header("Content-type: application/pdf");

            //print base64 decoded
            echo $pdf_b64;
        }
    }
    private function decodingToPdfSave ($base_64){
//        $pdf_base64 = "base64pdf.txt";
////Get File content from txt file
//        $pdf_base64_handler = fopen($pdf_base64,'r');
//        $pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
//        fclose ($pdf_base64_handler);
////Decode pdf content
        $pdf_decoded = base64_decode ($base_64);
//Write data back to pdf file
        $pdf = fopen ('test.pdf','w');
        fwrite ($pdf,$pdf_decoded);
//close output file
        fclose ($pdf);
        echo 'Done';
    }
}