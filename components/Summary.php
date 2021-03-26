<?php

namespace app\components;


use yii\base\Widget;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

class Summary extends Widget
{

    public function run()
    {
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            //->setUrl('http://pushkin.studio/testrgmekru/test.xml')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
            ->setData([
                'id' => 'c2afaaff-9e30-11e4-9c77-001e8c2d263f'
            ])
            ->send();
        if ($response->isOk) {
            $xml = new XmlParser();
            $result = $xml->parse($response);
            var_dump($result);
            if ($result['Error']) {
                return $result['Error']['Message'];
            } else {
                return $result['Value'];
            }
        } else {
            return 'Не удалось связаться БД - повторите попытку пзже.';
        }
    }
}