<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Contract;
use app\models\DraftContract;
use app\models\User;
use pantera\yii2\pay\sberbank\models\Invoice;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        return ExitCode::OK;
    }

    public function actionUpdate($message = 'hello world')
    {
        echo time() . "\n";
        $users = User::find()->all();
        foreach ($users as $user) {
            $contracts = new Client();
            $response = $contracts->createRequest()
                ->setMethod('GET')
                ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
                ->setData([
                    'id' => $user->id_db
                ])
                ->send();
            echo time() . "\n";
            if ($response->isOk) {
                $xml = new XmlParser();
                $result = $xml->parse($response);
                if ($result['Contract']) {
                    Contract::updateAllContract($user, $result['Contract']);
                    $user->with_date = $result['Withdate'];
                    $user->by_date = $result['Bydate'];
                    $user->full_name = $result['Name'];
                    $user->save();
                } else {
                    Contract::removeAllUserContract($user->id);
                }
            } else {
                \Yii::error('Не удалось связаться БД - повторите попытку позже.uid-' . $user->id_db . '\n');
                echo 'Не удалось связаться БД - повторите попытку позже.uid-' . $user->id_db;
            }


        }
        echo time() . "\n";
        return ExitCode::OK;
    }

    public function actionError1c()
    {
        $user = User::find()->one();
        $contracts = new Client();
        $response = $contracts->createRequest()
            ->setMethod('GET')
            ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
            ->setData([
                'id' => $user->id_db
            ])
            ->send();
        if (!$response->isOk) {
            \Yii::$app->mailer->compose()
                ->setFrom('no-reply@rgmek.ru')
                ->setTo(['it@rgmek.ru', 'lk@rgmek.ru'])
                ->setSubject('Ошибка связи с 1С!!!')
                ->setTextBody('Отвалилась 1С в ЛК!.')
                ->send();
        }

    }

    public function actionClearDraft()
    {
        $daysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        DraftContract::deleteAll(['<', 'send', $daysAgo]);
    }

    public function actionFixInvoiceStatus()
    {
        \Yii::error('Cron FixInvoice start: ' . time());
        $daysAgo = date('Y-m-d H:i:s', strtotime('-3 days'));
        $models = Invoice::find()
            ->where([
                'AND',
                ['=', 'status', 'I'],
                ['>', 'created_at', $daysAgo],
            ])->all();
        \Yii::error('Cron FixInvoice iteration: ' . count($models));

        $client = new Client();
        $ivoiceArr = [];
        foreach ($models as $invoice) {
            $invoice->status = 'I';
            $user = User::findOne($invoice->user_id);
            if ($user && $invoice->save(false)) {
                $ivoiceArr[] = $invoice->id;
                $user->setDataContracts();

                // Вызываем веб-контроллер через HTTP-запрос
                $response = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('https://lk.rgmek.ru/sberbank/default/complete')
                    ->setData(['mdOrder' => $invoice->orderId, 'lang' => 'ru'])
                    ->send();

               // \Yii::error('Invoice updated and request sent: ' . $invoice->id . ', Response: ' . json_encode($response->data));
            }
        }

        \Yii::error('Cron FixInvoice invoices: ' . implode(',', $ivoiceArr));
        \Yii::error('Cron FixInvoice finish: ' . time());
    }
}