<?php

namespace app\components;


use yii\base\Widget;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class Summary extends Widget
{

    public function run()
    {
        $uId = \Yii::$app->user->identity->id_db;
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
            ->setData([
                'id' => $uId
            ])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);
            \Yii::$app->session->set('fullUserName', $result['Name']);

            if ($result['Contract']) {
                $outputLeft = '';
                $outputEdo = '';
				if (!empty($result['Contract']['FullName'])){
                    $outputLeft = $this->toTemplateLeft($result['Contract'], $result['Withdate'], $result['Bydate']);
                    $outputEdo = $this->toTemplateEdo($result['Contract']);
				} else {
					foreach ($result['Contract'] as $contract){
                        $outputLeft.= $this->toTemplateLeft($contract, $result['Withdate'], $result['Bydate']);
                        $outputEdo.= $this->toTemplateEdo($contract);
					}
				}
                return $outputLeft. '||--||' . $outputEdo;
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку позже.';
        }
    }

    protected function toTemplateLeft ($contract, $fromDate, $byDate){
        ob_start();
        include __DIR__.'/tpl/contract_left.php';
        return ob_get_clean();
    }

    protected function toTemplateEdo ($contract){
        ob_start();
        include __DIR__.'/tpl/contract_edo.php';
        return ob_get_clean();
    }
}