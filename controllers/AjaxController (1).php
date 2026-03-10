<?php


namespace app\controllers;


use app\models\AttachForm;
use app\models\Contract;
use app\models\InvoiceSber;
use app\models\ReceiptForm;
use DOMDocument;
use pantera\yii2\pay\sberbank\Module;
use app\models\User;
use pantera\yii2\pay\sberbank\models\Invoice;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use Yii;
use XLSXWriter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class AjaxController extends Controller
{

    /* @var Module */
    public $module;

    public $layout = 'ajax';

    public function actionReSendVerification()
    {
        $user = User::findOne(['id' => Yii::$app->session->get('uId')]);
        $outputArr = [];
        if ($user) {
            $send = $user->sendVerification();
            if ($send === true) {
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
        $response = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contract_penalty', $data);
        if ($response['success'] !== false) {
            return $this->render('listPenalty', ['result' => $response['success']]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionListAktpp()
    {
        $data = \Yii::$app->request->post();
        $response = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/act_reception_transfer', $data);
        if ($response['success'] !== false) {
            return $this->render('listAktpp', ['result' => $response['success']]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionAccruedPaid()
    {
        $data = \Yii::$app->request->post();
        $response = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/report_accrual_payment', $data);
        if ($response['success'] !== false) {
            return $this->render('accruedPaid', ['result' => $response['success'], 'query' => $data]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionListInvoice()
    {
        $data = \Yii::$app->request->post();
        $response = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/invoices', $data);
        if ($response['success'] !== false) {
            return $this->render('listInvoice', ['result' => $response['success']]);
        } else {
            return json_encode(['error' => 'Не удалось связаться БД - повторите попытку позже.']);
        }

    }

    public function actionTransfer()
    {
        $data = \Yii::$app->request->post();
        $data['tu'] = json_decode($data['tu'], true);
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
            if (isset($responseArr['Error'])) {
                return $responseArr['Error']['Message'];
            } else {
                return 'Ваши данные успешно переданы!';
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }

    }

    public function actionReconciliation()
    {


        $data = \Yii::$app->request->get();
        $response = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/objects_list_ind', $data);
        if ($response['success'] !== false) {
            $responseArr = $response['success'];

            if ($responseArr['Object']['UIDObject'] == $data['uidobject']) {
                $outputArr = $responseArr['Object'];
            } else {
                foreach ($responseArr['Object'] as $object) {
                    if ($object['UIDObject'] == $data['uidobject']) {
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
                $row4 = array('', '', '', 'расчетный период ' . $outputArr['ActDate']);
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
                $writer->writeSheetHeader($sheet_name, $header, $col_options = ['widths' => [5, 8, 25, 25, 9, 18, 16, 16, 17], 'suppress_row' => true]);
                $writer->writeSheetRow($sheet_name, $row1);
                $writer->writeSheetRow($sheet_name, $row2, ['height' => 22, 'font-style' => 'bold', 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row3, ['height' => 22, 'font-style' => 'bold', 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row4, ['height' => 22, 'font-size' => 12, 'halign' => 'center']);
                $writer->writeSheetRow($sheet_name, $row5, ['height' => 22, 'font-size' => 12, 'color' => '#FF0000']);
                $writer->writeSheetRow($sheet_name, $row6, ['height' => 16, 'font-size' => 12, 'color' => '#FF0000']);
                $writer->writeSheetRow($sheet_name, []);
                $writer->writeSheetRow($sheet_name, $row7, ['height' => 51, 'font-size' => 10, 'wrap_text' => true, 'halign' => 'center', 'valign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'medium']);
                $row = [];
                if ($outputArr['Expand']['PU']['FullName']) {
                    $indications = (!empty($outputArr['Expand']['PU']['Indications'])) ? $outputArr['Expand']['PU']['Indications'] : 0;
                    $row = ['1', $outputArr['Expand']['PU']['ActNumber'], $outputArr['Expand']['PU']['ActName'], $outputArr['Expand']['PU']['ActAddress'], 'А', $outputArr['Expand']['PU']['Name'], $indications, $data[$outputArr['Expand']['PU']['UIDTU']], date('d.m.Y')];
                    $writer->writeSheetRow($sheet_name, $row, ['font-size' => 10, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true]);
                } else {
                    foreach ($outputArr['Expand']['PU'] as $pu) {
                        $indications = (!empty($pu['Indications'])) ? $pu['Indications'] : 0;
                        $row = ['1', $pu['ActNumber'], $pu['ActName'], $pu['ActAddress'], 'А', $pu['Name'], $indications, $data[$pu['UIDTU']], date('d.m.Y')];
                        $writer->writeSheetRow($sheet_name, $row, ['font-size' => 10, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true]);
                    }
                }

                //$writer->markMergedCell($sheet_name, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);

                return \Yii::$app->response->sendContentAsFile($writer->writeToString(), $filename);
            }
        }
    }

    public function actionAttach()
    {
        $model = new AttachForm();
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->photo = UploadedFile::getInstances($model, 'photo');
                $photo = $model->photo;
                $model->time = UploadedFile::getInstances($model, 'time');
                $time = $model->time;

                if ($model->sendMail(['89.tinik@gmail.com', 'sev@rgmek.ru'], $photo[0], $time[0])) {
                    return 'Ваши данные успешно отправлены!';
                } else {
                    return 'Что-то пошло не так - повторите попытку позже!';
                }
            } else {
                return 'Ошибка валидации - проверьте Ваши данные!';
            }

        }
    }


    public function actionClose()
    {
        return 'Сайт обновляется!';
    }

    public function actionGetIsuToken()
    {
        $authorizeUrl     = 'https://lkes.r-energiya.ru/Account/Authorize';
        $externalSystemId = '3BFE2123-5FEE-4EBD-B9BE-7367B7499FEE';
        $logFile = Yii::getAlias('@runtime/logs/get_token.log');

        $userId  = Yii::$app->user->identity->id;
        $userDbId = Yii::$app->user->identity->id_db;
        file_put_contents($logFile, "INN: " . Yii::$app->user->identity->inn . "\n", FILE_APPEND);

        $response = $this->sendToServer(
            'http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts',
            ['id' => $userDbId]
        );

        if ($response['success'] === false) {
            return $this->jsonError('failed_contracts_request');
        }

        $fiasArr = $response['success']['Address'] ?? null;

        // ==========================================================
        //              СБОРКА XML ЧЕРЕЗ DOMDocument
        // ==========================================================
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $root = $doc->createElement('UserData');
        $doc->appendChild($root);

        $root->appendChild($doc->createElement('ExternalSystemId', $externalSystemId));
        $metersNode = $doc->createElement('Meters');
        // Если fiasArr — массив, значит режим FIAS → добавляем Mode="1"
        if (is_array($fiasArr)) {
            $metersNode->setAttribute('Mode', '1');
        }
        $root->appendChild($metersNode);

        // ==========================================================
        //       1) ЕСЛИ ПРИШЛИ FIAS — ДОБАВЛЯЕМ ИХ
        // ==========================================================
        if (is_array($fiasArr)) {
//$fiasArr = ['ab9023de-beae-450d-a894-63b076a086a0', '3b6f6a76-e1fb-454e-bdac-7056631923ed'];
$i=0;
            foreach ($fiasArr as $meter) {
            $i++;
                $meter = $meter['fias'] ?? $meter ?? null;
                $meter = trim((string)$meter);
                if ($meter === '') continue;

                $m = $doc->createElement('Meter');
                $m->appendChild($doc->createElement('Fias', $meter));
                $metersNode->appendChild($m);
                if($i > 20)  break;
            }
        } else {
            // ======================================================
            //      2) ИНАЧЕ — ПОЛУЧАЕМ СЧЁТЧИКИ ПО ДОГОВОРАМ
            // ======================================================
            $contracts = Contract::find()
                ->where(['user_id' => $userId])
                ->asArray()
                ->all();

            foreach ($contracts as $c) {
                $contractInfo = $this->sendToServer(
                    'http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/objects_list',
                    ['uidcontracts' => $c['uid']]
                );

                if ($contractInfo['success'] === false) {
                    file_put_contents($logFile, "Bad contract structure: " . json_encode($contractInfo) . "\n", FILE_APPEND);
                    continue;
                }

                $meters = $this->extractMeters($contractInfo['success']);
//$meters = [
//['МИРТЕК', '1240162914973'],
//['МИРТЕК', '523T610931265']
//];
                foreach ($meters as [$type, $serial]) {
                    $m = $doc->createElement('Meter');
                    $m->appendChild($doc->createElement('Type', $type));
                    $m->appendChild($doc->createElement('Serial', $serial));
                    $metersNode->appendChild($m);
                }
            }
        }

        $xml = $doc->saveXML();

        // ==========================================================
        //                 ОТПРАВКА XML НА АВТОРИЗАЦИЮ
        // ==========================================================
        $ch = curl_init($authorizeUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $xml,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => ['Content-Type: application/xml; charset=utf-8'],
            CURLOPT_TIMEOUT         => 15,
            CURLOPT_SSL_VERIFYPEER  => true,
            CURLOPT_SSL_VERIFYHOST  => 2,
            CURLOPT_USERAGENT       => 'RGMEK-Bridge/1.0 (+php-curl)',
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $ctype= curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        // Оставляем лог
        file_put_contents($logFile,
            "[" . date("Y-m-d H:i:s") . "] HTTP:$code Content-Type:$ctype\n" .
            "SENT XML:\n$xml\n\nRESPONSE:\n" . mb_substr($body ?: $err, 0, 5000) . "\n\n",
            FILE_APPEND
        );

        if ($err || !$body) {
            return $this->jsonError('curl_failed', [
                'http' => $code,
                'detail' => $err ?: 'empty_response'
            ]);
        }

        // Извлекаем токен
        if (!preg_match('~<Token>([^<]+)</Token>~i', $body, $m)) {
            return $this->jsonError('no_token_in_response', [
                'http' => $code,
                'sample' => mb_substr($body, 0, 300)
            ]);
        }

        return $this->jsonSuccess(['token' => trim($m[1])]);
    }


    protected function sendToServer($url, $data = array(), $toArray = true, $method = 'GET', $xmlData = false)
    {
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url);
        if ($xmlData) {
            $request->setHeaders(['Content-Type' => 'application/xml; charset=utf-8'])
                ->setContent($data);
        } else {
            $request->setData($data);
        }
        $response = $request->send();
        if ($response->isOk) {
            //Yii::$app->session->set('response1C', $response->getContent());
            if ($toArray) {
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            } else {
                return ['success' => $response];
            }
        } else {
            return ['success' => false];
        }
    }

    private function extractMeters(array $data): array
    {
        $meters = [];

        $objects = $data['Object'] ?? [];
        if (isset($objects['Expand'])) {
            $objects = [$objects];
        }

        foreach ($objects as $obj) {
            $pu = $obj['Expand']['PU'] ?? null;
            if (!$pu) continue;

            if (isset($pu['Name'])) {
                // один счётчик
                $type   = trim($pu['Type'] ?? '');
                $serial = trim($pu['Name'] ?? '');
                if ($type && $serial) $meters[] = [$type, $serial];
            } else {
                // несколько PU
                foreach ($pu as $m) {
                    $type   = trim($m['Type'] ?? '');
                    $serial = trim($m['Name'] ?? '');
                    if ($type && $serial) $meters[] = [$type, $serial];
                }
            }
        }

        return $meters;
    }
    private function jsonError($msg, $extra = [])
    {
        return json_encode(['ok' => false, 'error' => $msg] + $extra, JSON_UNESCAPED_UNICODE);
    }

    private function jsonSuccess($data = [])
    {
        return json_encode(['ok' => true] + $data, JSON_UNESCAPED_UNICODE);
    }
}

