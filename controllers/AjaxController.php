<?php


namespace app\controllers;


use app\models\User;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use Yii;
use XLSXWriter;


class AjaxController extends Controller
{

    public $layout = 'ajax';

    public function actionReSendVerification()
    {
        $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
        $outputArr = [];
        if ($user) {
            if ($send = $user->sendVerification() === true) {
                $uMethod = (Yii::$app->session->get('vMethod') == 1) ? 'телефон' : 'e-mail';
                $outputArr['success'] = 'Проверьте Ваш ' . $uMethod . '.';
            } else {
                $outputArr['error'] = $send['error'];
            }
        } else {
            $outputArr['error'] = 'Ваша сессия просрочена - заполните предыдущую форму заново.';
        }
        return json_encode($outputArr);
    }

    public function actionListPenalty()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_penalty')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listPenalty', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionListAktpp()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/act_reception_transfer')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listAktpp', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
    public function actionAccruedPaid()
    {
        $data = \Yii::$app->request->post();
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/report_accrual_payment')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('accruedPaid', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
    public function actionListInvoice()
    {
        $data = \Yii::$app->request->post();
        $data['quantity'] = 'full';
        //$data['uidcontracts']='b95aa4a7-9f5e-11e4-9c77-001e8c2d263f';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_account')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            return $this->render('listInvoice', ['result' => $xml->parse($response)]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }
    public function actionTransfer()
    {
        $data = \Yii::$app->request->post();
        $data['tu'] = json_decode($data['tu'], true);
//        var_dump($data);
//        die();
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/send_indication')
            ->setData($data)
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $responseArr = $xml->parse($response);
            if (isset($responseArr['Error'])){
                return $responseArr['Error']['Message'];
            } else {
                return 'Ваши данные успешно переданны!';
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }

    }
    public function actionReconciliation ()
    {


        $data = \Yii::$app->request->get();
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/objects_list_ind')
            ->setData(['uidcontracts'=>$data['uidcontracts']])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $responseArr = $xml->parse($response);

            if ($responseArr['Object']['UIDObject'] == $data['uidobject']) {
                $outputArr = $responseArr['Object'];
            } else {
                foreach ($responseArr['Object'] as $object){
                    if ($object['UIDObject'] == $data['uidobject']){
                        $outputArr = $object;
                        break;
                    }
                }
            }
            if (!empty($outputArr)) {


                $filename = "act.xlsx";

                $header = array("string");
                $row1 = array();
                $row2 = array('', '', '', 'АКТ');
                $row3 = array('', '', '', 'фиксации показаний приборов учета электрической энергии');
                $row4 = array('', '', '', 'расчетный период '.$outputArr['ActDate']);
                $row5 = array($outputArr['ActContract']);
                $row6 = array($outputArr['ActСontractor']);
                $row7 = array('№
пп
', '№
объекта', 'Наименование объекта', 'Адрес
местонахождения объекта', 'Акт/
реакт', 'Номер прибора
учета', 'Показание  на начало
расчетного периода', 'Показание  на конец
расчетного периода', 'Дата записи показаний
(число, месяц, год)');
                $sheet_name = 'Sheet1';
                $writer = new XLSXWriter();
                $writer->writeSheetHeader($sheet_name, $header, $col_options = ['widths' => [6, 10, 31, 31, 10, 20, 17, 17, 17], 'suppress_row' => true]);
                $writer->writeSheetRow($sheet_name, $row1);
                $writer->writeSheetRow($sheet_name, $row2, ['height' => 22, 'font-style' => 'bold', 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row3, ['height' => 22, 'font-style' => 'bold', 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row4, ['height' => 22, 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row5, ['height' => 22, 'font-size' => 12, 'color' => '#FF0000']);
                $writer->writeSheetRow($sheet_name, $row6, ['height' => 16, 'font-size' => 12, 'color' => '#FF0000']);
                $writer->writeSheetRow($sheet_name, []);
                $writer->writeSheetRow($sheet_name, $row7, ['height' => 51, 'font-size' => 10, 'wrap_text' => true, 'halign' => 'center', 'valign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium']);
                $row=[];
                if ($outputArr['Expand']['PU']['FullName']){
                    $indications = (!empty($outputArr['PU']['Indications']))?$outputArr['Expand']['PU']['Indications']:0;
                    $row = ['1', $outputArr['Expand']['PU']['ActNumber'], $outputArr['Expand']['PU']['ActName'], $outputArr['Expand']['PU']['ActAddress'], 'А', $outputArr['Expand']['PU']['Name'], $indications, $data[$outputArr['Expand']['PU']['UIDTU']], date('d.m.Y')];
                    $writer->writeSheetRow($sheet_name, $row, ['font-size' => 10, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true]);
                } else {
                    foreach ($outputArr['Expand']['PU'] as $pu){
                        $indications = (!empty($pu['Indications']))?$pu['Indications']:0;
                        $row = ['1', $pu['ActNumber'], $pu['ActName'], $pu['ActAddress'], 'А', $pu['Name'], $indications, $data[$pu['UIDTU']], date('d.m.Y')];
                        $writer->writeSheetRow($sheet_name, $row, ['font-size' => 10, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true]);
                    }
                }

                //$writer->markMergedCell($sheet_name, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);

                return \Yii::$app->response->sendContentAsFile($writer->writeToString(), $filename);
            }
        }
    }
}
