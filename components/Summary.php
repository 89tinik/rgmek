<?php

namespace app\components;


use yii\base\Widget;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class Summary extends Widget
{

    public function run()
    {
//        $uId = \Yii::$app->user->identity->id_db; расскоментировать UID
        $uId = 'c2afaaff-9e30-11e4-9c77-001e8c2d263f';
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            //->setUrl('http://pushkin.studio/testrgmekru/test.xml')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
            ->setData([
                'id' => $uId
            ])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);
            if ($result['Contract']) {
                $output = '';
                foreach ($result['Contract'] as $contract){
                    $output.= $this->toTemplate($contract);
                }
                return $output;
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку пзже.';
        }
    }

    protected function toTemplate ($contract){
        ob_start();
        include __DIR__.'/tpl/contract_left.php';
        return ob_get_clean();
    }
}